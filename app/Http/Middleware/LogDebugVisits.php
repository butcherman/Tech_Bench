<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LogDebugVisits
{
    protected $ignore = ['_token', 'token'];
    protected $redact = ['password', 'password_confirmation', 'oldPass', 'newPass', 'newPass_confirmation'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(config('logging.channels.daily.level') === 'debug')
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

        return $next($request);
    }
}
