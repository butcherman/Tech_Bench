<?php

namespace App\Listeners\Notify\User;

use App\Events\User\PasswordChangedEvent;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class PasswordChangedListener
{
    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Notification::send($event->user, new PasswordChangedNotification);
    }
}
