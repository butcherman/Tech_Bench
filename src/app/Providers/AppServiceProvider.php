<?php

namespace App\Providers;

use App\Actions\Misc\CheckDatabaseError;
use App\Policies\GatePolicy;
use App\Services\_Base\TraceContext;
use App\Services\Misc\CacheFacadeHelper;
use App\Services\User\GetMailableUsers;
use App\Services\User\UserPermissionsService;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Azure\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

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
        $this->app->bind('CacheData', CacheFacadeHelper::class);

        // DbQueryFacade
        $this->app->bind('DbException', CheckDatabaseError::class);

        // User Permission Facade
        $this->app->bind('UserPermissions', UserPermissionsService::class);

        // Tracing Context for logging information
        $this->app->scoped(TraceContext::class, fn () => new TraceContext);

        // Get Mailable Facade
        // $this->app->bind('GetMailable', GetMailableUsers::class);

        //  Gate to determine if the Administration link should show up on the navigation menu
        Gate::define('admin-link', [GatePolicy::class, 'adminLink']);

        //  Gate to determine if the Reports link should show up on the navigation menu
        Gate::define('reports-link', [GatePolicy::class, 'reportsLink']);

        // Gate to determine if the user is an installer level user
        Gate::define('is-installer', [GatePolicy::class, 'isInstaller']);

        // Listen to Socialite Events
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('azure', Provider::class);
        });

        // Remove Data Wrapping from Resources
        JsonResource::withoutWrapping();

        // Add Trace ID to all Queued Jobs
        Queue::createPayloadUsing(function () {
            $context = app(TraceContext::class);

            return $context->has()
                ? ['trace_id' => $context->id()]
                : [];
        });

        // Add Trace ID context to all Queued Jobs
        Queue::before(function (JobProcessing $event) {
            $payload = $event->job->payload();

            $traceId = data_get(
                $payload,
                'trace_id',
            );

            if (! $traceId) {
                $traceId = app(TraceContext::class)->id();
            } else {
                app(TraceContext::class)->set($traceId);
            }

            Context::add([
                'trace_id' => $traceId,
            ]);
        });

        Queue::after(function (JobProcessed $event) {
            Log::withoutContext(['trace_id']);

            app(TraceContext::class)->clear();
        });

        Queue::exceptionOccurred(function (JobExceptionOccurred $event) {
            Log::withoutContext(['trace_id']);

            app(TraceContext::class)->clear();
        });

        // Add Trace ID to all console jobs
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            $traceId = app(TraceContext::class)->id();

            Context::add([
                'trace_id' => $traceId,
            ]);
        });
    }
}
