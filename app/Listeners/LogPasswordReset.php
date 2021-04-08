<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogPasswordReset
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
