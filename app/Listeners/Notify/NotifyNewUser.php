<?php

namespace App\Listeners\Notify;

use Illuminate\Support\Str;
use App\Events\Admin\NewUserCreated;
use App\Models\UserInitialize;
use App\Notifications\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyNewUser implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(NewUserCreated $event)
    {
        //  Create a User Initialization Link
        UserInitialize::create([
            'username' => $event->user->username,
            'token'    => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($token));
    }
}
