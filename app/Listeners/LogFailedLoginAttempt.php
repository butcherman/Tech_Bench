<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogFailedLoginAttempt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Log::warning('User Tried to login with invalid credentials', ['Username' => $event->credentials['username'], 'IP Address' => \Request::ip()]);
    }
}
