<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        'App\Events\UserPasswordChanged' => [
            'App\Listeners\LogUserPasswordChanged',
        ],
        'App\Events\UserInitializedEvent' => [
            'App\Listeners\LogUserInitialized',
        ],

        /**
         * File Events
         */
        'App\Events\Home\ImageUploadedEvent' => [
            'App\Listeners\Home\LogImageUploaded',
        ],
        'App\Events\Home\UploadedFileEvent' => [
            'App\Listeners\Home\LogUploadedFile',
        ],
        'App\Events\Home\DownloadedFileEvent' => [
            'App\Listeners\Home\LogDownloadedFile',
        ],

        /**
         * Customer Events
         */
        'App\Events\Customers\NewCustomerCreated' => [
            'App\Listeners\Customers\LogNewCustomerCreated',
        ],
        'App\Events\Customers\CustomerDetailsUpdated' => [
            'App\Listeners\Customers\LogUpdatedCustomer',
        ],
        'App\Events\Customers\CustomerLinkedEvent' => [
            'App\Listeners\Customers\LogCustomerLinked',
        ],
        'App\Events\Customers\CustomerDeactivatedEvent' => [
            'App\Listeners\Customers\LogDeactivatedCustomer',
        ],
        //  Customer Equipment
        'App\Events\Customers\Equipment\CustomerEquipmentAddedEvent' => [
            'App\Listeners\Customers\Equipment\LogNewCustomerEquipment',
        ],
        'App\Events\Customers\Equipment\CustomerEquipmentUpdatedEvent' => [
            'App\Listeners\Customers\Equipment\LogUpdatedCustomerEquipment',
        ],
        'App\Events\Customers\Equipment\CustomerEquipmentDeletedEvent' => [
            'App\Listeners\Customers\Equipment\LogDeletedCustomerEquipment',
        ],
        'App\Events\Customers\Equipment\CustomerEquipmentForceDeletedEvent' => [
            'App\Listeners\Customers\Equipment\LogForceDeletedCustomerEquipment',
        ],
        'App\Events\Customers\Equipment\CustomerEquipmentRestoredEvent' => [
            'App\Listeners\Customers\Equipment\LogRestoredCustomerEquipment',
        ],
        //  Customer Contacts
        'App\Events\Customers\Contacts\CustomerContactAddedEvent' => [
            'App\Listeners\Customers\Contacts\LogAddedCustomerContact'
        ],
        'App\Events\Customers\Contacts\CustomerContactUpdatedEvent' => [
            'App\Listeners\Customers\Contacts\LogUpdatedCustomerContact'
        ],
        'App\Events\Customers\Contacts\CustomerContactDeletedEvent' => [
            'App\Listeners\Customers\Contacts\LogDeletedContact'
        ],
        'App\Events\Customers\Contacts\CustomerContactDownloadedEvent' => [
            'App\Listeners\Customers\Contacts\LogDownloadedCustomerContact'
        ],
        'App\Events\Customers\Contacts\CustomerContactForceDeletedEvent' => [
            'App\Listeners\Customers\Contacts\LogForceDeletedCustomerContact',
        ],
        'App\Events\Customers\Contacts\CustomerContactRestoredEvent' => [
            'App\Listeners\Customers\Contacts\LogRestoredCustomerContact',
        ],
        //  Customer Notes
        'App\Events\Customers\Notes\CustomerNoteAddedEvent' => [
            'App\Listeners\Customers\Notes\LogAddedCustomerNote'
        ],
        'App\Events\Customers\Notes\CustomerNoteUpdatedEvent' => [
            'App\Listeners\Customers\Notes\LogUpdatedCustomerNote'
        ],
        'App\Events\Customers\Notes\CustomerNoteDeletedEvent' => [
            'App\Listeners\Customers\Notes\LogDeletedNote'
        ],
        // 'App\Events\Customers\Notes\CustomerNoteDownloadedEvent' => [
        //     'App\Listeners\Customers\Notes\LogDownloadedCustomerNote'
        // ],
        'App\Events\Customers\Notes\CustomerNoteForceDeletedEvent' => [
            'App\Listeners\Customers\Notes\LogForceDeletedCustomerNote',
        ],
        'App\Events\Customers\Notes\CustomerNoteRestoredEvent' => [
            'App\Listeners\Customers\Notes\LogRestoredCustomerNote',
        ],
        //  Customer Files
        'App\Events\Customers\Files\CustomerFileAddedEvent' => [
            'App\Listeners\Customers\Files\LogAddedCustomerFile'
        ],
        'App\Events\Customers\Files\CustomerFileUpdatedEvent' => [
            'App\Listeners\Customers\Files\LogUpdatedCustomerFile'
        ],
        'App\Events\Customers\Files\CustomerFileDeletedEvent' => [
            'App\Listeners\Customers\Files\LogDeletedFile'
        ],
        'App\Events\Customers\Files\CustomerFileForceDeletedEvent' => [
            'App\Listeners\Customers\Files\LogForceDeletedCustomerFile',
        ],
        'App\Events\Customers\Files\CustomerFileRestoredEvent' => [
            'App\Listeners\Customers\Files\LogRestoredCustomerFile',
        ],
        //  Customer Administration
        'App\Events\Customers\Admin\CustomerIdChangedEvent' => [
            'App\Listeners\Customers\Admin\LogChangedCustomerId',
        ],
        'App\Events\Customers\Admin\CustomerRestoredEvent' => [
            'App\Listeners\Customers\Admin\LogRestoredCustomer',
        ],
        'App\Events\Customers\Admin\CustomerForceDeletedEvent' => [
            'App\Listeners\Customers\Admin\LogForceDeletedCustomer',
        ],
        'App\Events\Customers\Admin\CustomerFileTypeCreatedEvent' => [
            'App\Listeners\Customers\Admin\LogFileTypeCreated',
        ],
        'App\Events\Customers\Admin\CustomerFileTypeUpdatedEvent' => [
            'App\Listeners\Customers\Admin\LogFileTypeUpdated',
        ],
        'App\Events\Customers\Admin\CustomerFileTypeDeletedEvent' => [
            'App\Listeners\Customers\Admin\LogFileTypeDeleted',
        ],
        'App\Events\Customers\Admin\CustomerFileTypeDeletedErrorEvent' => [
            'App\Listeners\Customers\Admin\LogFileTypeDeletedError',
        ],

        /**
         * Tech Tip Events
         */
        'App\Events\TechTips\TechTipCreatedEvent' => [
            'App\Listeners\TechTips\LogTechTipCreated',
            'App\Listeners\Notify\NotifyNewTechTip',
        ],
        'App\Events\TechTips\TechTipUpdatedEvent' => [
            'App\Listeners\TechTips\LogTechTipUpdated',
            'App\Listeners\Notify\NotifyUpdatedTechTip',
        ],
        'App\Events\TechTips\TechTipDeletedEvent' => [
            'App\Listeners\TechTips\LogTechTipDeleted',
        ],
        //  Tech Tip Comments
        'App\Events\TechTips\TechTipCommentCreatedEvent' => [
            'App\Listeners\TechTips\LogNewTechTip',
            'App\Listeners\Notify\NotifyOfNewTechTipComment',
        ],
        'App\Events\TechTips\TechTipCommentDeletedEvent' => [
            'App\Listeners\TechTips\LogDeletedTechTip',
        ],
        'App\Events\TechTips\TechTipCommentUpdatedEvent' => [
            'App\Listeners\TechTips\LogUpdatedTechTip',
        ],
        'App\Events\TechTips\TechTipCommentFlaggedEvent' => [
            'App\Listeners\TechTips\LogFlaggedTechTip',
            'App\Listeners\Notify\NotifyOfFlaggedComment',
        ],
        //  Tech Tip Administration
        'App\Events\TechTips\Admin\TipTypeCreatedEvent' => [
            'App\Listeners\TechTips\Admin\LogTipTypeCreated',
        ],
        'App\Events\TechTips\Admin\TipTypeUpdatedEvent' => [
            'App\Listeners\TechTips\Admin\LogTipTypeUpdated',
        ],
        'App\Events\TechTips\Admin\TipTypeDeletedEvent' => [
            'App\Listeners\TechTips\Admin\LogTipTypeDeleted',
        ],

        /**
         * User Administration Events
         */
        'App\Events\Admin\NewUserCreated' => [

            'App\Listeners\Notify\NotifyNewUser',
            'App\Listeners\Admin\LogUserCreated',
        ],
        'App\Events\Admin\UserDeactivatedEvent' => [

            'App\Listeners\Admin\LogUserDeactivated',
        ],
        'App\Events\Admin\UserReactivatedEvent' => [

            'App\Listeners\Admin\LogUserReactivated',
        ],
        'App\Events\Admin\UserUpdatedEvent' => [
            'App\Listeners\Admin\LogUserUpdated',
        ],
        'App\Events\Admin\UserRoleCreatedEvent' => [
            'App\Listeners\Admin\LogUserRoleCreated',
        ],
        'App\Events\Admin\UserRoleUpdatedEvent' => [
            'App\Listeners\Admin\LogUserRoleUpdated',
        ],
        'App\Events\Admin\UserRoleDeletedEvent' => [
            'App\Listeners\Admin\LogUserRoleDeleted',
        ],



        // 'App\Events\NewTechTipEvent' => [
        //     'App\Listeners\NotifyOfNewTechTip',
        // ],
        // 'App\Events\NewTipCommentEvent' => [
        //     'App\Listeners\NotifyOfNewComment',
        // ],
        // 'App\Events\FlaggedTipCommentEvent' => [
        //     'App\Listeners\NotifyOfFlaggedComment',
        // ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        //
    }
}
