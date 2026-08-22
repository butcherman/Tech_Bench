<?php

namespace App\Actions\Fortify;

use App\Exceptions\Auth\InvalidMultiFactorAuthException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as BaseRedirectIfTwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable extends BaseRedirectIfTwoFactorAuthenticatable
{
    /**
     * Override the default Two Factor check to look for a save device code and
     * allow for email as an option to get the 2FA code.
     *
     * @param  Request  $request
     * @param  callable  $next
     */
    public function handle($request, $next): mixed
    {
        $user = $this->validateCredentials($request);

        // Is 2FA enabled?
        if (! $this->twoFactorEnabled()) {
            return $next($request);
        }

        // Is device already trusted?
        if ($this->isRememberedDevice($request, $user)) {
            return $next($request);
        }

        // Has 2FA been setup if required?
        if ($this->requiresTwoFactorSetup($user)) {
            return $this->redirectToTwoFactorSetup($request, $user);
        }

        // Is 2FA even necessary?
        if ($this->twoFactorNeeded($user)) {
            return $next($request);
        }

        // Show 2FA Challange
        return $this->redirectToChallenge($request, $user);
    }

    /**
     * Determine if two factor is enabled
     */
    private function twoFactorEnabled(): bool
    {
        return (bool) config('auth.twoFa.enabled');
    }

    /**
     * Determine if two factor is enabled, but not required or setup
     */
    private function twoFactorNeeded(User $user): bool
    {
        return (bool) ! config('auth.twoFa.required') && is_null($user->two_factor_via);
    }

    /**
     * Determine whether this device has previously been trusted.
     */
    private function isRememberedDevice(Request $request, User $user): bool
    {
        if (! config('auth.twoFa.allow_save_device')) {
            return false;
        }

        $token = $request->cookie('remember_device');

        return $token && $user->validateDeviceToken($token);
    }

    /**
     * Determine whether the user needs to configure two-factor authentication.
     */
    private function requiresTwoFactorSetup(User $user): bool
    {
        return config('auth.twoFa.required')
            && is_null($user->two_factor_via);
    }

    /**
     * Redirect the user to the appropriate two-factor setup flow.
     */
    private function redirectToTwoFactorSetup(Request $request, User $user): mixed
    {
        Auth::login($user, $request->input('remember'));

        $methods = $this->availableTwoFactorMethods();
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->boolean('remember'),
        ]);

        if (count($methods) === 0) {
            throw new InvalidMultiFactorAuthException('Two-factor authentication is required, but no methods are enabled.');
        }

        if (count($methods) > 1) {
            $request->session()->put(
                'url.intended',
                url()->previous()
            );

            return redirect()->route('two-factor.setup.index');
        }

        return match ($methods[0]) {
            'email' => redirect()->route('two-factor.setup.email'),
            'authenticator' => redirect()->route('two-factor.setup.authenticator'),
        };
    }

    /**
     * Redirect the user to the proper Challenge page
     */
    private function redirectToChallenge(Request $request, User $user)
    {
        return match ($user->two_factor_via) {
            'email' => $this->twoFactorChallengeResponse($request, $user),
            'authenticator' => $this->twoFactorChallengeResponse($request, $user),
            default => throw new InvalidMultiFactorAuthException('Invalid two-factor authentication method')
        };
    }

    /**
     * Get the two-factor methods currently available to users.
     */
    private function availableTwoFactorMethods(): array
    {
        return collect(config('auth.twoFa.methods'))
            ->filter()
            ->keys()
            ->all();
    }
}
