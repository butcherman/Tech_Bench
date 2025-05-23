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
         * Application Level Events
         */
        'App\Events\Config\UrlChangedEvent' => [
            'App\Listeners\Config\UrlChangedListener',
        ],
        'App\Events\Admin\AdministrationEvent' => [],

        /**
         * Maintenance Events
         */
        'Spatie\Backup\Events\BackupManifestWasCreated' => [
            'App\Listeners\Maintenance\AddVersionToBackup',
        ],
        'Spatie\Backup\Events\BackupWasSuccessful' => [
            'App\Listeners\Maintenance\LogSuccessfulBackup',
        ],
        'Spatie\Backup\Events\BackupHasFailed' => [
            'App\Listeners\Maintenance\LogFailedBackup',
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
            'App\Listeners\User\SendPasswordResetNotification',
        ],
        'App\Events\Feature\FeatureChangedEvent' => [
            'App\Listeners\Feature\RebuildFeaturePermissionsListener',
        ],

        /**
         * User Profile Events
         */
        'App\Events\User\EmailChangedEvent' => [
            'App\Listeners\Notify\User\EmailChangedListener',
        ],
        'App\Events\User\PasswordChangedEvent' => [
            'App\Listeners\User\LogPasswordChanged',
            'App\Listeners\User\SendPasswordChangedNotification',
        ],

        /**
         * Office 365 Authentication Events
         */
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Azure\AzureExtendSocialite::class.'@handle',
        ],

        /**
         * File Events
         */
        'App\Events\File\FileDataDeletedEvent' => [
            'App\Listeners\File\DeleteFileFromDiskIfNotInUse',
        ],

        /**
         * Customer Events for broadcasting
         */
        'App\Events\Customer\CustomerAlertEvent' => [],
        'App\Events\Customer\CustomerSlugChangedEvent' => [],

        /**
         * Tech Tip Events
         */
        'App\Events\TechTips\TechTipEvent' => [
            'App\Listeners\TechTips\UpdateTipFilesListener',
            'App\Listeners\TechTips\TechTipNotificationListener',
        ],
        'App\Events\TechTips\TipCommentedEvent' => [
            'App\Listeners\TechTips\TechTipCommentNotificationListener',
        ],
        'App\Events\TechTips\TipCommentFlaggedEvent' => [
            'App\Listeners\TechTips\TipCommentFlaggedListener',
        ],

        /**
         * File Link Events
         */
        'App\Events\FileLinks\FileUploadedFromPublicEvent' => [
            'App\Listeners\FileLinks\NotifyUserOfFileUploadListener',
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
