<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check to see if this is the first time that the application is being
 * accessed.  If so, start the setup Wizard
 */
class CheckForInit
{
    //  Routes that are not affected by this middleware
    protected $bypassRoutes = [
        'user.settings.password',
        'user-password.update',
        'admin.config.set',
        'admin.email.set',
        'admin.email.test',
        'admin.users.password-policy.set',
        'logout',
        'init.welcome',
        'init.step-1',
        'init.step-2',
        'init.step-3',
        'init.step-4',
        'init.step-5',
    ];

    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.first_time_setup')
            && config('app.env') !== 'testing'
            && $request->user()
            && ! in_array(Route::current()->getName(), $this->bypassRoutes)
        ) {
            if ($request->user()->user_id !== 1) {
                Log::critical('An unauthorized user tried to gain access to the First Time Setup Wizard', $request->user()->toArray());
                abort(403);
            }

            Log::info('First Time Setup Required, redirecting to Initialization Wizard');

            return redirect(route('init.welcome'))->with('warning', 'Setup Required');
        }

        return $next($request);
    }
}
