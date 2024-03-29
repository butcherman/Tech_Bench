<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LogDebugVisits
{
    //  These items should never be logged
    protected $ignore = ['_token', 'token'];
    //  These items are logged with masked input to show that they did exist during the session
    protected $redact = ['password', 'password_confirmation', 'current_password'];


    /**
\     * When Debug Logging is one, log every page visit and all $request data
     */
    public function handle(Request $request, Closure $next)
    {
        $user = isset(Auth::user()->user_id) ? Auth::user()->full_name : \Request::ip();
        $requestData = $request->request->all();

        Log::debug('Route '.Route::currentRouteName().' visited by '.$user);

        if(!empty($requestData))
        {
            foreach($this->redact as $i)
            {
                if(Arr::exists($requestData, $i))
                {
                    $requestData[$i] = '[REDACTED]';
                }
            }
            foreach($this->ignore as $i)
            {
                if(Arr::exists($requestData, $i))
                {
                    unset($requestData[$i]);
                }
            }
            Log::debug('Submitted Data ', $requestData);
        }

        return $next($request);
    }
}
