<?php

namespace App\Http\Middleware;

use App\Exceptions\Init\FirstTimeSetupAlreadyCompletedException;
use App\Exceptions\Init\InvalidUserAccessingSetupException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Initialize App Middleware will make sure that the app has not already been
| initialized (you can only do the wizard once). The built in Admin User is
| also the only user that can run the wizard
|-------------------------------------------------------------------------------
*/

class InitializeApp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('app.first_time_setup')) {
            throw new FirstTimeSetupAlreadyCompletedException;
        }

        if ($request->user()->user_id !== 1) {
            throw new InvalidUserAccessingSetupException;
        }

        return $next($request);
    }
}
