<?php

namespace App\Providers;

use App\Actions\Misc\CheckDatabaseError;
use App\Policies\GatePolicy;
use App\Services\Misc\CacheHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services and register the customer Facades.
     */
    public function boot(): void
    {
        // CacheFacade
        $this->app->bind('CacheData', CacheHelper::class);

        // DbQueryFacade
        $this->app->bind('DbException', CheckDatabaseError::class);

        //  Gate to determine if the Administration link should show up on the navigation menu
        Gate::define('admin-link', [GatePolicy::class, 'adminLink']);

        //  Gate to determine if the Reports link should show up on the navigation menu
        Gate::define('reports-link', [GatePolicy::class, 'reportsLink']);
    }
}
