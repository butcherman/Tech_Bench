<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * If the users password has expired, force them to change their password
 */
class CheckPasswordExpire
{
    /**
     * Check to see if the users password has expired
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  Check to see if we are logged in and not visiting a bypass route
        if (
            $request->user()->password_expires &&
            $request->user()->password_expires < Carbon::now()
        ) {
            Log::stack(['auth', 'daily'])
                ->notice('User '.$request->user()->full_name.' is being forced to change their password');

            return redirect()
                ->route('user.change-password.show')
                ->withErrors(['password' => __('user.password_expired')]);
        }

        return $next($request);
    }
}
