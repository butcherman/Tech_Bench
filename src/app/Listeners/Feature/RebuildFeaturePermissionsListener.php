<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;
use Laravel\Pennant\Feature;

class RebuildFeaturePermissionsListener
{
    /**
     * Force all Feature Permissions to be rebuilt
     */
    public function handle(FeatureChangedEvent $event): void
    {
        Feature::purge();
    }
}
