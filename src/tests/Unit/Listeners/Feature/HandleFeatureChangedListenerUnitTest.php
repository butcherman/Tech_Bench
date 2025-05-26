<?php

namespace Tests\Unit\Listeners\Feature;

use App\Events\Feature\FeatureChangedEvent;
use Laravel\Pennant\Feature;
use Mockery\MockInterface;
use Tests\TestCase;

class HandleFeatureChangedListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Feature::shouldReceive('purge')->once();

        FeatureChangedEvent::dispatch();
    }
}
