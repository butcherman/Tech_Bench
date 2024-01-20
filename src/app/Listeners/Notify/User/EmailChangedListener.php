<?php

namespace App\Listeners\Notify\User;

use App\Events\User\EmailChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailChangedListener
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
    public function handle(EmailChangedEvent $event): void
    {
        //
    }
}
