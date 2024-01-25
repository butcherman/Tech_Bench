<?php

namespace App\Listeners\User;

use App\Events\User\UserCreatedEvent;

class CreateUserSettingsEntry
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
