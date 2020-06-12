<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     * In addition, it is set as the URL generator's root namespace.
     */
    protected $namespace = 'App\Http\Controllers';
    public const HOME    = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        // $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapUserRoutes();
        $this->mapAdminRoutes();
        $this->mapCustomerRoutes();
    }

    /**
     * Define the basic web routes for the application.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
    }

    protected function mapUserRoutes()
    {
        Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/user.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/administration.php'));
    }

    protected function mapCustomerRoutes()
    {
        Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/customers.php'));
    }

    /**
     * Define the "api" routes for the application.
     * These routes are typically stateless.
     */
    // protected function mapApiRoutes()
    // {
    //     Route::prefix('api')
    //             ->middleware('api')
    //             ->namespace($this->namespace)
    //             ->group(base_path('routes/api.php'));
    // }
}
