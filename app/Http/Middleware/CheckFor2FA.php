<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFor2FA
{
    /**
     * Handle Two-Factor Authentication
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Any authenticated route will check to make sure user has verified themselves
         */
        if(session()->missing('2fa_verified')) {
            /**
             * Check to see if a remember device token exists
             */
            if($rememberToken = $request->cookie('remember_device')) {
                if($request->user()->validateDeviceToken($rememberToken)) {
                    $request->session()->put('2fa_verified', true);
                    return $next($request);
                }
            }

            $request->user()->generateVerificationCode();

            return redirect(route('2fa.index'));
        }

        return $next($request);
    }
}
