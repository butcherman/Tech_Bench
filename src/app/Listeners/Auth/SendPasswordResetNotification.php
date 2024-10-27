<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\PasswordReset;

class SendPasswordResetNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        //
    }
}
