<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        /**
         * Authentication Events
         */
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\Auth\LogFailedLoginAttempt',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\LogSuccessfulLogout',
        ],
        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\Auth\LogLockout',
        ],
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\Auth\LogPasswordReset',
        ],

        /**
         * Office 365 Authentication Events
         */
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Azure\AzureExtendSocialite::class.'@handle',
        ],

        /**
         * User Profile Events
         */
        'App\Events\User\EmailChangedEvent' => [
            'App\Listeners\Notify\User\EmailChangedListener',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
