<?php

namespace App\Providers;

use App\Actions\Misc\BuildTimezoneList;
use App\Actions\Misc\CheckDatabaseError;
use App\Contracts\ReportingContract;
use App\Policies\GatePolicy;
use App\Services\Misc\CacheHelper;
use App\Services\Report\UserReports;
use App\Services\User\GetMailableUsers;
use App\Services\User\UserPermissionsService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
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

        // Timezone Facade
        $this->app->bind('TimezoneList', BuildTimezoneList::class);

        // User Permission Facade
        $this->app->bind('UserPermissions', UserPermissionsService::class);

        // Get Mailable Facade
        $this->app->bind('GetMailable', GetMailableUsers::class);

        //  Gate to determine if the Administration link should show up on the navigation menu
        Gate::define('admin-link', [GatePolicy::class, 'adminLink']);

        //  Gate to determine if the Reports link should show up on the navigation menu
        Gate::define('reports-link', [GatePolicy::class, 'reportsLink']);

        // Listen to Socialite Events
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });

        // Remove Data Wrapping from Resources
        JsonResource::withoutWrapping();
    }
}
