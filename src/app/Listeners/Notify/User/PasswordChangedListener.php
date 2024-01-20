<?php

namespace App\Listeners\Notify\User;

use App\Events\User\PasswordChangedEvent;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Support\Facades\Notification;

class PasswordChangedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Notification::send($event->user, new PasswordChangedNotification);
    }
}
