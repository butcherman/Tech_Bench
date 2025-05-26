<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Pennant\Feature;

class HandleFeatureChangedListener
{
    /**
     * Purge the Features table to allow it to be rebuilt.
     */
    public function handle(FeatureChangedEvent $event): void
    {
        Feature::purge();
    }
}
