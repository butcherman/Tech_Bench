<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\PasswordReset;

class LogPasswordReset
{
    /**
     * Handle the event
     */
    public function handle(PasswordReset $event)
    {
        Log::stack(['auth', 'user'])->notice('Password for User '.$event->user->full_name.' has been reset',
        [
            'User ID'    => $event->user->user_id,
            'Username'   => $event->user->username,
            'IP Address' => \Request::ip()
        ]);
    }
}
