<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckForInit
{
    //  Routes that are not affected by the middleware
    protected $bypassRoutes = [
        'settings.password.index',
        'settings.password.store',
        'logout',
        'init.step-1',
        'init.step-2',
        'init.step-3',
        'init.step-4',
        'init.step-5',
        'admin.set-config',
        'admin.set-email',
        'admin.test-email',
    ];

    /**
     * Check to see if the app has been setup for the first time
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()
            && config('app.first_time_setup')
            && ! in_array(Route::current()->getName(), $this->bypassRoutes)) {
            return redirect(route('init.step-1'));
        }

        return $next($request);
    }
}
