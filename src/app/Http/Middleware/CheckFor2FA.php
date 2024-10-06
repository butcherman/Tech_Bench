<?php

// TODO - Refactor

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * If Two Factor Authentication is enabled, verify that the user has properly
 * authenticated via 2FA
 */
class CheckFor2FA
{
    /**
     * Handle Two-Factor Authentication
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Any authenticated route will check to make sure user has verified themselves
        if (config('auth.twoFa.required') && session()->missing('2fa_verified')) {
            // Check to see if a remember device token exists and is valid
            if ($rememberToken = $request->cookie('remember_device')) {
                if ($request->user()->validateDeviceToken($rememberToken)) {
                    //  If device is valid, we will attach verification and move on
                    $request->session()->put('2fa_verified', true);

                    return $next($request);
                }
            }

            $request->user()->generateVerificationCode();

            app('redirect')->setIntendedUrl($request->path());

            return redirect(route('2fa.show'));
        }

        return $next($request);
    }
}
