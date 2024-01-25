<?php

namespace App\Listeners\Notify\User;

use App\Events\User\ResendWelcomeEvent;

class ResendWelcomeEmail
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
    public function handle(ResendWelcomeEvent $event): void
    {
        //
    }
}
