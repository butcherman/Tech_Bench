<?php

namespace Tests\Unit\Jobs\TechTip;

use App\Jobs\TechTip\ProcessTipFilesJob;
use App\Models\TechTip;
use App\Services\TechTip\TechTipFileService;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessTipFilesJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $tip = TechTip::factory()->create();

        $this->mock(TechTipFileService::class, function (MockInterface $mock) {
            $mock->shouldReceive('checkTipFileFolder')->once()->with(TechTip::class);
        });

        ProcessTipFilesJob::dispatch($tip);
    }
}
