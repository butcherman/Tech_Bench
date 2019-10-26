<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //  Gate to determine if the user is an installer or not
        Gate::define('is_installer', 'App\Policies\GatePolicy@isInstaller');

        //  Gate to allow the Administration link in the Navbar
        Gate::define('allow_admin', 'App\Policies\GatePolicy@seeAdminLink');

        //  Gate to allow the user to see the Reports link in the Navbar
        Gate::define('allow_report', 'App\Policies\GatePolicy@seeReportLink');

        //  Gate to determine if the user is allowed an action based on their permissions table
        Gate::define('hasAccess', 'App\Policies\GatePolicy@hasAccess');
    }
}
