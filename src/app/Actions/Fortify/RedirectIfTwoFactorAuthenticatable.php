<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as BaseRedirectIfTwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable extends BaseRedirectIfTwoFactorAuthenticatable
{
    /**
     * Override the default Two Factor check to look for a save device code and
     * allow for email as an option to get the 2FA code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     */
    public function handle($request, $next): mixed
    {
        $user = $this->validateCredentials($request);

        // If 2FA is disabled, move on
        if (! config('auth.twoFa.required')) {
            return $next($request);
        }

        // If user has a Remember Device token, validate it and login
        if ($rememberToken = $request->cookie('remember_device') && config('auth.twoFa.allow_save_device')) {
            $valid = $user->validateDeviceToken($rememberToken);

            if ($valid) {
                return $next($request);
            }
        }

        // If only authenticator is allowed, but user is not setup, redirect
        if (
            is_null($user->two_factor_confirmed_at) &&
            (! config('auth.twoFa.allow_via_email') && config('auth.twoFa.allow_via_authenticator'))
        ) {
            app('redirect')->setIntendedUrl(route('two-factor.setup.authenticator'));

            return $next($request);
        }

        // If the user has not setup 2FA yet, redirect them to set it up
        if (
            is_null($user->two_factor_via) &&
            (config('auth.twoFa.allow_via_email') && config('auth.twoFa.allow_via_authenticator'))
        ) {
            app('redirect')->setIntendedUrl(route('two-factor.setup.index'));

            return $next($request);
        }

        return $this->twoFactorChallengeResponse($request, $user);
    }
}
