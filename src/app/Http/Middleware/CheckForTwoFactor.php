<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Check to see if the user has authenticated via 2FA if enabled.
|-------------------------------------------------------------------------------
*/

class CheckForTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        // If 2FA is disabled, move on.
        if (! config('auth.twoFa.required')) {
            return $next($request);
        }

        // If 2FA session variable exists, and is true, move on.
        if ($request->session()->has('2fa_verified')) {
            if ($request->session()->get('2fa_verified')) {
                return $next($request);
            }
        }

        // If the user has already verified this device, move on.
        if ($rememberToken = $request->cookie('remember_device')) {
            if ($request->user()->validateDeviceToken($rememberToken)) {
                $request->session()->put('2fa_verified', true);

                return $next($request);
            }
        }

        // 2FA Needed - Generate a code and email it to the user
        $request->user()->generateVerificationCode();

        // Set the page the user is trying to currently visit for later redirect
        app('redirect')->setIntendedUrl($request->path());

        // Send user to Verify 2FA Page
        return redirect(route('2fa.show'));
    }
}
