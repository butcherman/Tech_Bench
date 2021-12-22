<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    /**
     * Handle the event
     */
    public function handle(Logout $event)
    {
        Log::info('User '.$event->user->full_name.' logged out', [
            'User ID'    => $event->user->user_id,
            'Username'   => $event->user->username,
            'IP Address' => \Request::ip()
        ]);
    }
}
