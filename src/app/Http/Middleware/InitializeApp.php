<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InitializeApp
{
    /**
     * Initialize App Middleware will make sure that the app has not already been
     * initialized (you can only do the wizard once)
     * The built in Admin User is also the only user that can run the wizard
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('app.first_time_setup')) {
            Log::error($request->user()->username.' is trying to access the setup wizard when the app is already setup');
            abort(403, 'The First Time Setup can only be run once');
        }

        if ($request->user()->user_id !== 1) {
            Log::error(
                'A user other than the built in Admin is trying to access the setup wizard',
                $request->user()->toArray()
            );
        }

        return $next($request);
    }
}
