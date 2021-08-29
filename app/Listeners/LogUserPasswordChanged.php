<?php

namespace App\Listeners;

use App\Events\UserPasswordChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserPasswordChanged
{
    /**
     * Handle the event
     */
    public function handle(UserPasswordChanged $event)
    {
        Log::channel('user')->info('User '.$event->user->username.' has updated their password');
    }
}
