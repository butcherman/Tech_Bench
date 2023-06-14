<?php

namespace App\Listeners\Notify\User;

use App\Events\User\UserCreatedEvent;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyNewUser
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
