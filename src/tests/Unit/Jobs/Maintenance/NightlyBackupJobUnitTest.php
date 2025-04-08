<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Jobs\Maintenance\NightlyBackupJob;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Spatie\Backup\Events\BackupHasFailed;
use Tests\TestCase;

class NightlyBackupJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        config(['backup.nightly_backup' => true]);

        $this->partialMock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyBackupDiskSpace')->once()->andReturn(true);
        });

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', [], ConsoleOutputService::class);

        NightlyBackupJob::dispatch();
    }

    public function test_handle_config_disabled(): void
    {
        config(['backup.nightly_backup' => false]);

        $this->partialMock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldNotHaveReceived('verifyBackupDiskSpace');
        });

        NightlyBackupJob::dispatch();
    }

    public function test_handle_no_disk_space(): void
    {
        config(['backup.nightly_backup' => true]);

        Event::fake();

        $this->partialMock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyBackupDiskSpace')->once()->andReturn(false);
        });

        $this->expectException(BackupFailedException::class);

        NightlyBackupJob::dispatch();

        Event::assertDispatched(BackupHasFailed::class);
    }
}
