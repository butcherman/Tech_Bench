<?php

namespace App\Providers;

use App\Policies\GatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //  Gate to determine if the Administration link should show up on the navigation menu
        Gate::define('admin-link', [GatePolicy::class, 'adminLink']);
        //  Gate to determine if the Reports link should show up on the navigation menu
        Gate::define('reports-link', [GatePolicy::class, 'reportsLink']);
    }
}
