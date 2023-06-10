<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        Log::stack(['daily', 'auth', 'user'])->info('User '.$event->user->full_name.' logged out', [
            'User ID' => $event->user->user_id,
            'Username' => $event->user->username,
            'IP Address' => \Request::ip(),
        ]);
    }
}
