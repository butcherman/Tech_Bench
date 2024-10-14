<?php

// TODO - Refactor

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check to see if this is the first time that the application is being
 * accessed.  If so, start the setup Wizard
 */
class CheckForInit
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.first_time_setup')
            && config('app.env') !== 'testing'
            && $request->user()
        ) {
            if ($request->user()->user_id !== 1) {
                Log::critical('An unauthorized user tried to gain access to the First Time Setup Wizard', $request->user()->toArray());
                abort(403);
            }

            Log::info('First Time Setup Required, redirecting to Initialization Wizard');

            return redirect(route('init.welcome'));
        }

        return $next($request);
    }
}
