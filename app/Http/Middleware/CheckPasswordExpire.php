<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class CheckPasswordExpire
{
    //  Routes that are not affected by the password expiring
    protected $bypassRoutes = [
        'user.password',
        'user-password.update',
        'logout',
    ];

    /**
     * Check to see if the users password has expired
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  Check to see if we are logged in and not visiting a bypass route
        if ($request->user() && ! in_array(Route::current()->getName(), $this->bypassRoutes)) {
            //  check to see if the password is expired
            if ($request->user()->password_expires && $request->user()->password_expires < Carbon::now()) {
                Log::stack(['auth', 'user'])
                    ->notice('User '.$request->user()->full_name.' is being forced to change their password');

                return redirect()
                    ->route('user.password')
                    ->withErrors(['password' => __('user.password_expired')]);
            }
        }

        return $next($request);
    }
}
