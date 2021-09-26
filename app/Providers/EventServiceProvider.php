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


        // 'App\Events\NewUserCreated' => [
        //     'App\Listeners\NotifyNewUser',
        // ],
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
