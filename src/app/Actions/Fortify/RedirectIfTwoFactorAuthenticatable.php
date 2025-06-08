<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as BaseRedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable extends BaseRedirectIfTwoFactorAuthenticatable
{
    public function handle($request, $next): mixed
    {
        $user = $this->validateCredentials($request);

        // If 2FA is disabled, move on
        if (!config('auth.twoFa.required')) {
            return $next($request);
        }

        // If user has a Remember Device token, validate it and login
        if ($rememberToken = $request->cookie('remember_device') && config('auth.twoFa.allow_save_device')) {
            $valid = $user->validateDeviceToken($rememberToken);

            if ($valid) {
                return $next($request);
            }
        }

        // If user is using the Authenticator App for 2FA, redirect
        if ($user->two_factor_secret && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }
}
