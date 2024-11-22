<?php

namespace App\Listeners\User;

use App\Events\User\UserEmailChangedEvent;
use App\Mail\User\EmailChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    }
}
