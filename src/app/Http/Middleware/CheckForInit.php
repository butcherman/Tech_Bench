<?php

namespace App\Http\Middleware;

use App\Exceptions\Init\InvalidUserAccessingSetupException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/*
|-------------------------------------------------------------------------------
| Check to see if this is the first time the application is being accessed.
| If so, start the setup wizard.
|-------------------------------------------------------------------------------
*/

class CheckForInit
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.first_time_setup') && $request->user()) {
            // Only the built in admin user can access wizard
            if ($request->user()->user_id !== 1) {
                throw new InvalidUserAccessingSetupException;
            }

            Log::info('First Time Setup Required, redirecting to Initialization Wizard');

            return redirect(route('init.welcome'));
        }

        return $next($request);
    }
}
