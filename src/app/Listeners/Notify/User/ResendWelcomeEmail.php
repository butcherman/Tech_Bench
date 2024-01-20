<?php

namespace App\Listeners\Notify\User;

use App\Events\User\ResendWelcomeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
