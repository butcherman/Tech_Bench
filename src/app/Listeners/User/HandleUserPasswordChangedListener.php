<?php

namespace App\Listeners\User;

use App\Events\User\UserPasswordChangedEvent;
use App\Mail\Auth\PasswordChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleUserPasswordChangedListener implements ShouldQueue
{
    /**
     * Log the event, and send the user an email notifying them of the change
     */
    public function handle(UserPasswordChangedEvent $event): void
    {
        Mail::to($event->user)->send(new PasswordChangedMail($event->user));

        Log::stack(['daily', 'auth'])
            ->info('User '.$event->user->full_name.' has updated their password', [
                'User ID' => $event->user->user_id,
                'Username' => $event->user->username,
                'IP Address' => request()->ip(),
            ]);
    }
}
