<?php

namespace Tests\Unit\Jobs\FileLink;

use App\Jobs\FileLink\ProcessLinkFilesJob;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkFileService;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessLinkFilesJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->mock(FileLinkFileService::class, function (MockInterface $mock) {
            $mock->shouldReceive('checkLinkFileFolder')
                ->once()
                ->with(FileLink::class);
            $mock->shouldReceive('checkLinkFilePermission')
                ->once()
                ->with(FileLink::class);
        });

        ProcessLinkFilesJob::dispatch(FileLink::factory()->create());
    }
}
