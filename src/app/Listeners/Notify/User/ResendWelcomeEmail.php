<?php

namespace App\Listeners\Notify\User;

use App\Events\User\ResendWelcomeEvent;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ResendWelcomeEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ResendWelcomeEvent $event): void
    {
        $token = Str::uuid();

        UserInitialize::firstOrCreate(
            ['username' => $event->user->username],
            ['token' => $token],
        )->update(['token' => $token]);

        Log::debug('Updated Init Link for '.$event->user->username.'. New Token - '.$token);

        Notification::send($event->user, new SendWelcomeEmail($token));
    }
}
