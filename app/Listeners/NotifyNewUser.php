<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use App\Events\NewUserCreated;
use App\Models\UserInitialize;
use App\Notifications\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyNewUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserCreated  $event
     * @return void
     */
    public function handle(NewUserCreated $event)
    {
        UserInitialize::create([
            'username' => $event->user->username,
            'token'    => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($event->user, $token));
    }
}
