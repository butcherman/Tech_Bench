<?php

namespace App\Http\Middleware;

use Closure;

class CheckBrowser
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
        //  Browser check will only fire if the HTTP_USER_AGENT string is actually set by the browser
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            //  Check for Internet Explorer 11
            if(preg_match("/Trident\/7.0;(.*)rv:11.0/", $_SERVER["HTTP_USER_AGENT"], $match) != 0)
            {
                return response()->make(view('error.426'), 426);
            }
        }

        return $next($request);
    }
}
