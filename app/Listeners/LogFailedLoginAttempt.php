<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogFailedLoginAttempt
{
    /**
     * Create the event listener.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Handle the event.
     */
    public function handle(Failed $event)
    {
        Log::channel('auth')->warning('User Tried to login with invalid credentials', ['Username' => $event->credentials['username'], 'IP Address' => \Request::ip()]);
    }
}
