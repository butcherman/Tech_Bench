<?php

namespace App\Listeners\User;

use App\Events\User\PasswordChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogPasswordChanged
{
    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Log::stack(['daily', 'user', 'auth'])->info('User '.$event->user->username.' has updated their password');
    }
}
