<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Jobs\Maintenance\RunBackupJob;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Mockery\MockInterface;
use Tests\TestCase;

class RunBackupJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        // Clear any potential Atomic Locks
        Artisan::call('cache:clear');

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', [], ConsoleOutputService::class);

        RunBackupJob::dispatch();
    }

    public function test_handle_no_disk_space(): void
    {
        // Clear any potential Atomic Locks
        Artisan::call('cache:clear');

        Notification::fake();

        $this->mock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyBackupDiskSpace')
                ->once()
                ->andReturn(false);
        });

        $this->expectException(BackupFailedException::class);

        RunBackupJob::dispatch();

        Notification::assertCount(1);
    }
}
