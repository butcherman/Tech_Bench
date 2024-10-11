<?php

namespace App\Listeners\User;

use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPasswordResetNotification implements ShouldQueue
{
    /**
     * Event is triggered when a user resets their forgotten password
     */
    public function handle(PasswordReset $event): void
    {
        Notification::send($event->user, new PasswordChangedNotification);
    }
}
