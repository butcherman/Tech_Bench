<?php

namespace App\Listeners\Notify\User;

use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PasswordResetListener
{
    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        Log::debug('Sending password change email to '.$event->user->email);

        Notification::send($event->user, new PasswordChangedNotification);
    }
}
