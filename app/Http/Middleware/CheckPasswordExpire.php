<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        $user = $request->user();
        
        if($user->password_expires != null)
        {
            $passExp = new Carbon(($user->password_expires));

            if(!empty($passExp) && Carbon::now() > $passExp && !empty(config('users.passExpires')))
            {
                Log::notice('User ID-'.Auth::user()->user_id.' is being forced to change their password.');
                $request->session()->flash('change_password', 'change_password');
                return redirect()->route('changePassword');
            }
        }
        
        return $next($request);
    }
}
