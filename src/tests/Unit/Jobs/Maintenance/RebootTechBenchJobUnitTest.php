<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Events\Admin\AdministrationEvent;
use App\Jobs\Maintenance\RebootTechBenchJob;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class RebootTechBenchJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Event::fake();

        $this->partialMock(DockerControlService::class, function (MockInterface $mock) {
            $mock->shouldReceive('rebootAllContainers')->once();
        });

        RebootTechBenchJob::dispatch();

        Event::assertDispatched(AdministrationEvent::class);
    }
}
