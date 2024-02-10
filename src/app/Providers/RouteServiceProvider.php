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
    /**
     * The path to your application's "home" route
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        /**
         * Register all files in the routes folder
         */
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function () {
            $routes = glob(base_path('routes/web/*.php'));
            foreach ($routes as $route) {
                require $route;
            }
        });
    }
}
