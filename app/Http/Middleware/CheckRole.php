<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    //  Check and verify tha tthe user has permission to visit the requested page
    public function handle($request, Closure $next)
    {
        if($request->user() === null)
        {
            Log::alert($request->route().' denied for Guest');
//            return response()->view('errors.401', [], 401);
            abort(401);
        }
        $actions = $request->route()->getAction();
        $roles   = isset($actions['roles']) ? $actions['roles'] : null;
        
        if($request->user()->hasAnyRole($roles) || !$roles)
        {
            return $next($request);
        }

        Log::alert($request->url().' denied for user '.$request->user());
//        return response()->view('errors.401', [], 401);
        abort(401);
    }
}
