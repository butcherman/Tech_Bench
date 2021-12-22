<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     *  If a user is not logged in, they will be re-routed to login page
     */
    protected function redirectTo($request)
    {
        if(!$request->expectsJson()) {
            return route('login.index');
        }
    }
}
