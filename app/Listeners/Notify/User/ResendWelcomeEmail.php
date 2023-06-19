<?php

namespace App\Listeners\Notify\User;

use App\Events\User\ResendWelcomeEvent;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ResendWelcomeEmail
{
    /**
     * Delete any previous Welcome Link and create a new one
     * Send new Welcome link to user
     */
    public function handle(ResendWelcomeEvent $event): void
    {
        $oldLink = UserInitialize::where('username', $event->user->username)->first();
        if ($oldLink) {
            $oldLink->delete();
        }

        UserInitialize::create([
            'username' => $event->user->username,
            'token' => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($token));
    }
}
