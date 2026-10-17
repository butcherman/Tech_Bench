<?php

namespace App\Listeners\User;

use App\Events\User\UserEmailChangedEvent;
use App\Mail\User\EmailChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleUserEmailChangedListener implements ShouldQueue
{
    /**
     * Send the user an email letting them know that their email address has
     * been changed
     */
    public function handle(UserEmailChangedEvent $event): void
    {
        Mail::to($event->originalEmail)
            ->send(new EmailChangedMail($event->user));

        Log::stack(['auth', 'daily'])
            ->info('User '.$event->user->username.' has change their email address.', [
                'user_id' => $event->user->user_id,
                'original_email' => $event->originalEmail,
                'new_email' => $event->user->email,
            ]);
    }
}
