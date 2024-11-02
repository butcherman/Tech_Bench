<?php

namespace App\Listeners\Config;

use App\Events\Config\UrlChangedEvent;

class HandleUrlChangeListener
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
    public function handle(UrlChangedEvent $event): void
    {
        // TODO - Handle the event
    }
}
