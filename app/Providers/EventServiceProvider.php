<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * @codeCoverageIgnore
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        /**
         * Authentication Events
         */
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLoginAttempt',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\LogPasswordReset',
        ],

        /**
         * Office 365 Authentication Events
         */
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Azure\AzureExtendSocialite::class.'@handle',
        ],

        /**
         * User Administration Events
         */
        'App\Events\Admin\UserCreatedEvent' => [
            'App\Listeners\Notify\NotifyNewUser',
            'App\Listeners\Admin\CreateUserSettingsEntry',
        ],
        'App\Events\Admin\UserRoleCreatedEvent' => [
            'App\Listeners\Admin\CreateRolePermissions',
        ],
        'App\Events\Admin\UserRoleUpdatedEvent' => [
            'App\Listeners\Admin\UpdateRolePermissions',
        ],

        /**
         * Customer Events
         */
        'App\Events\Customer\CustomerEquipmentCreatedEvent' => [
            'App\Listeners\Notify\Customers\NewCustomerEquipmentNotification',
        ],
        'App\Events\Customer\CustomerEquipmentUpdatedEvent' => [
            'App\Listeners\Notify\Customers\UpdatedCustomerEquipmentNotification',
        ],
        'App\Events\Customer\CustomerContactCreatedEvent' => [
            'App\Listeners\Notify\Customers\NewCustomerContactNotification',
        ],
        'App\Events\Customer\CustomerContactUpdatedEvent' => [
            'App\Listeners\Notify\Customers\UpdatedCustomerContactNotification',
        ],
        'App\Events\Customer\CustomerNoteCreatedEvent' => [
            'App\Listeners\Notify\Customers\NewCustomerNoteNotification',
        ],
        'App\Events\Customer\CustomerNoteUpdatedEvent' => [
            'App\Listeners\Notify\Customers\UpdatedCustomerNoteNotification',
        ],
        'App\Events\Customer\CustomerFileCreatedEvent' => [
            'App\Listeners\Notify\Customers\NewCustomerFileNotification',
        ],
        'App\Events\Customer\CustomerFileUpdatedEvent' => [
            'App\Listeners\Notify\Customers\UpdatedCustomerFileNotification',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        //
    }
}
