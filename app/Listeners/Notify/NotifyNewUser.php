<?php

namespace App\Listeners\Notify;

use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\UserInitialize;
use App\Events\Admin\UserCreatedEvent;
use App\Notifications\User\SendWelcomeEmail;

class NotifyNewUser implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(UserCreatedEvent $event)
    {
        //  Create a User Initialization Link
        UserInitialize::create([
            'username' => $event->user->username,
            'token'    => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($token));
    }
}
