<?php

namespace App\Listeners\User;

use App\Events\User\PasswordChangedEvent;
use Illuminate\Support\Facades\Log;

class LogPasswordChanged
{
    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Log::stack(['user', 'auth'])->info('User '.$event->user->username.
            ' has updated their password');

    }
}
