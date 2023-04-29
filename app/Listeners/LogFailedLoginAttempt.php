<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogFailedLoginAttempt
{
    /**
     * Handle the event
     */
    public function handle(Failed $event)
    {
        Log::channel('auth')->warning('User tried to login with invalid credentials', [
            'Username' => $event->credentials['username'],
            'IP Address' => \Request::ip(),
            'Attempt Number' => session('failed_login') + 1,
        ]);
    }
}
