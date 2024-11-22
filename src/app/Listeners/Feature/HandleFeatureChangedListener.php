<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Pennant\Feature;

class HandleFeatureChangedListener implements ShouldQueue
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
