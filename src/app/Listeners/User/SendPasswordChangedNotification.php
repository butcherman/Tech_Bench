<?php

namespace App\Listeners\User;

use App\Events\User\PasswordChangedEvent;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPasswordChangedNotification implements ShouldQueue
{
    /**
     * Event is triggered when a user changes their Password
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Notification::send($event->user, new PasswordChangedNotification);
    }
}
