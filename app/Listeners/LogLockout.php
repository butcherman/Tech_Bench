<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogLockout
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
        Log::warning('Username '.$event->request->username.' has been locked out due to too many failed login attempts', ['Username' => $event->request->username, 'IP Address' => \Request::ip()]);
    }
}
