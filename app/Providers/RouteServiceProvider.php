<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * @codeCoverageIgnore
 */
class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function()
        {
            //  Basic Login and Logout Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/auth.php'));

            //  First time setup Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/init.php'));

            //  System Administration Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));

            // System Reports Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/reports.php'));

            //  Basic Authenticated Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            //  Customer Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/cust.php'));

            //  Equipment Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/equip.php'));

            //  Tech Tip Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/tips.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function(Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
