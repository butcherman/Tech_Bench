<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Actions\Maintenance\CleanImageFolders;
use App\Jobs\Maintenance\CleanImageFoldersJob;
use Mockery\MockInterface;
use Tests\TestCase;

class CleanImageFoldersJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->partialMock(CleanImageFolders::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->once()->andReturn(0);
        });

        CleanImageFoldersJob::dispatch();
    }
}
