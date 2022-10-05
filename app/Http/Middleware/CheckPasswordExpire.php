<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
*   If a user's password has expired, they must update it before being allowed to continue
*/
class CheckPasswordExpire
{
    //  Routes that are not affected by the password expiring
    protected $bypassRoutes = [
        'password.index',
        'password.store',
        'logout',
    ];

    /**
     *  Check to see if the users password has expired recently
     */
    public function handle(Request $request, Closure $next)
    {
        //  TODO - turn this back on!!!!!!!
        //  Check to see if we are logged in
        // if($request->user() && !in_array(Route::current()->getName(), $this->bypassRoutes))
        // {
        //     //  check to see if the password is expired
        //     if($request->user()->password_expires && $request->user()->password_expires < Carbon::now())
        //     {
        //         Log::stack(['auth', 'user'])->notice('User '.$request->user()->full_name.' is being forced to change their password');
        //         return redirect()->route('password.index')->with(['message' => 'Your Password Has Expired.  You must change your password to continue', 'type' => 'warning']);
        //     }
        // }

        return $next($request);
    }
}
