<?php

namespace App\Listeners\Notify\User;

use App\Events\User\UserCreatedEvent;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class NotifyNewUser implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UserCreatedEvent $event): void
    {
        UserInitialize::create([
            'username' => $event->user->username,
            'token' => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($token));
    }
}
