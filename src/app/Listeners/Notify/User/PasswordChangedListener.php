<?php

namespace App\Listeners\Notify\User;

use App\Events\User\PasswordChangedEvent;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PasswordChangedListener
{
    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Log::debug('Sending password change email to '.$event->user->email);

        Notification::send($event->user, new PasswordChangedNotification);
    }
}