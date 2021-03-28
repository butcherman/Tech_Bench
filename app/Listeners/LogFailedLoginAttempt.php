<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogFailedLoginAttempt
{
    public function handle(Failed $event)
    {
        Log::warning('User Tried to login with invalid credentials', ['Username' => $event->credentials['username'], 'IP Address' => \Request::ip()]);
    }
}
