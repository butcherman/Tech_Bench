<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        if($request->user() === null)
        {
            Log::alert($request->route().' denied for Guest');
            return response('Insufficient Permissions', 401);
        }
        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        
        if($request->user()->hasAnyRole($roles)  || !$roles)
        {
            return $next($request);
        }
        Log::alert($request->route().' denied for user '.$request->user());
        return response('Insufficient Permissions', 401);
    }
}
