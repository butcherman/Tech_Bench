<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use App\Notifications\NewUserWelcome;

use App\User;
use App\UserInitialize;
use App\Events\NewUserCreated;

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
        $user = User::find($event->user->user_id);
        UserInitialize::create([
            'username' => $user->username,
            'token'    => $hash = strtolower(Str::random(30)),
        ]);
        Log::info('New User Welcome link created for new user '.$user->full_name.'.');

        Notification::send($user, new NewUserWelcome($user, $hash));
    }
}
