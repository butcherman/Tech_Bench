<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckPasswordExpire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && !(Route::current()->getName() === 'changePassword' || Route::current()->getName() === 'logout'))
        {
            //  Verify that the users password has not expired
            if(Auth::user()->password_expires !== null)
            {
                $passExp = new Carbon((Auth::user()->password_expires));
                if(Carbon::now() > $passExp )
                {
                    Log::notice('User ID-'.Auth::user()->user_id.' is being forced to change their password.');
                    $request->session()->flash('change_password', 'change_password');
                    return redirect()->route('changePassword');
                }
            }
        }

        return $next($request);
    }
}
