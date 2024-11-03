<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;
use Laravel\Pennant\Feature;

class HandleFeatureChangedListener
{
    /**
     * Handle the event.
     *
     * @codeCoverageIgnore
     */
    public function handle(FeatureChangedEvent $event): void
    {
        Feature::purge();
    }
}
