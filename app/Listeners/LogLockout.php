<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;

class LogLockout
{
    public function handle(Lockout $event)
    {
        Log::warning('Username '.$event->request->username.' has been locked out due to too many failed login attempts', ['Username' => $event->request->username, 'IP Address' => \Request::ip()]);
    }
}
