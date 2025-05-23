<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Check to see if the users password has expired
|-------------------------------------------------------------------------------
*/

class CheckPasswordExpiration
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->password_expires) {
            if ($request->user()->password_expires < Carbon::now()) {
                Log::stack(['auth', 'app'])
                    ->notice(
                        'User '.$request->user()->full_name.
                            ' is being force to change their expired password'
                    );

                return redirect()
                    ->route('user.change-password.show')
                    ->withErrors(['password' => __('user.password.expired')]);
            }
        }

        return $next($request);
    }
}
