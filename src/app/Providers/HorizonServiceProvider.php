<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

/**
 * @codeCoverageIgnore
 */
class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register the Horizon gate.
     *
     * Only Installers can register to Horizon URL in non-local environment
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function (User $user) {
            return $user->role_id === 1;
        });
    }
}
