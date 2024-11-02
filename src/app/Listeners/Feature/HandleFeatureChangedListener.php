<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;

class HandleFeatureChangedListener
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
    public function handle(FeatureChangedEvent $event): void
    {
        // TODO - Do Something.......
    }
}
