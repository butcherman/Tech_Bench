<?php

namespace App\Listeners\Notify\User;

use App\Events\User\UserCreatedEvent;

class NotifyNewUser
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
    public function handle(UserCreatedEvent $event): void
    {
        //
    }
}
