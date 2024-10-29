<?php

namespace App\Listeners\User;

use App\Events\User\UserEmailChangedEvent;
use App\Mail\User\EmailChangedMail;
use Illuminate\Support\Facades\Mail;

class HandleUserEmailChangedListener
{
    /**
     * Handle the event.
     */
    public function handle(UserEmailChangedEvent $event): void
    {
        Mail::to($event->originalEmail)
            ->send(new EmailChangedMail($event->user));
    }
}
