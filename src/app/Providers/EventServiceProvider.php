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
            'App\Listeners\Notify\User\PasswordResetListener',
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
            'App\Listeners\Notify\User\PasswordChangedListener',
            'App\Listeners\User\LogPasswordChanged',
        ],
        'App\Events\User\UserCreatedEvent' => [
            'App\Listeners\Notify\User\NotifyNewUser',
            'App\Listeners\User\CreateUserSettingsEntry',
        ],
        'App\Events\User\ResendWelcomeEvent' => [
            'App\Listeners\Notify\User\ResendWelcomeEmail',
        ],

        /**
         * Office 365 Authentication Events
         */
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Azure\AzureExtendSocialite::class . '@handle',
        ],

        /**
         * File Events
         */
        'App\Events\File\FileDataDeletedEvent' => [
            'App\Listeners\File\DeleteFileFromDiskIfNotInUse',
        ],

        /**
         * Customer Events
         */
        'App\Events\Customer\CustomerEvent' => [],
        'App\Events\Customer\CustomerSiteEvent' => [],
        'App\Events\Customer\CustomerAlertEvent' => [],
        'App\Events\Customer\CustomerContactEvent' => [],
        'App\Events\Customer\CustomerEquipmentEvent' => [],
        'App\Events\Customer\CustomerEquipmentDataEvent' => [],
        'App\Events\Customer\CustomerNoteEvent' => [],
        'App\Events\Customer\CustomerFileEvent' => [],

        /**
         * Tech Tip Events
         */
        'App\Events\TechTips\TechTipEvent' => [
            'App\Listeners\TechTips\UpdateTipFilesListener',
            'App\Listeners\Notify\TechTips\TechTipNotification',
        ],
        'App\Events\TechTips\TipCommentedEvent' => [
            'App\Listeners\Notify\TechTips\NotifyOfCommentListener',
        ],
        'App\Events\TechTips\TipCommentFlaggedEvent' => [
            'App\Listeners\Notify\TechTips\NotifyOfFlaggedTipListener',
        ],

        /**
         * File Link Events
         */
        'App\Events\FileLinks\FileLinkEvent' => [
            'App\Listeners\FileLinks\UpdateLinkFilesListener'
        ],
        'App\Events\FileLinks\FileUploadedFromPublicEvent' => [
            'App\Listeners\Notify\FileLinks\FileUploadFromPublicListener'
        ]
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
