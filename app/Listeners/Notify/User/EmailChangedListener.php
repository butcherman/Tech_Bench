<?php

namespace App\Listeners\Notify\User;

use App\Events\User\EmailChangedEvent;
use App\Models\User;
use App\Notifications\User\EmailChangedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EmailChangedListener
{
    /**
     * Handle the event.
     */
    public function handle(EmailChangedEvent $event): void
    {
        $oldUser = new User;
        $oldUser->email = $event->oldEmail;

        Log::stack(['daily', 'user'])->notice('Email Address for '.$event->user->username.' has been changed', $event->user->toArray());
        Notification::send($oldUser, new EmailChangedNotification($event->user->email));
    }
}