<?php

namespace Tests\Unit\Services\Maintenance;

use App\DTO\Maintenance\BackupStatus;
use App\DTO\Maintenance\BackupSummary;
use App\Services\Maintenance\BackupService;
use App\Services\Maintenance\BackupStatusService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupStatusServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | status()
    |---------------------------------------------------------------------------
    */
    public function test_status_no_backups(): void
    {
        Storage::fake('backups');

        $testObj = new BackupStatusService(new BackupService);
        $res = $testObj->status();

        $this->assertInstanceOf(BackupStatus::class, $res);
        $this->assertFalse($res->healthy);
        $this->assertEquals('No backups have been created.', $res->message);
    }

    public function test_status(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupStatusService(new BackupService);
        $res = $testObj->status();

        $this->assertInstanceOf(BackupStatus::class, $res);
        $this->assertTrue($res->healthy);
        $this->assertNull($res->message);
        $this->assertEquals(9, $res->backupCount);
        $this->assertInstanceOf(BackupSummary::class, $res->latest);
    }

    public function test_status_unhealthy(): void
    {
        $testSummary = new BackupSummary(
            name: 'backup-1.zip',
            size: 1234,
            modified: CarbonImmutable::parse(Carbon::now()->subYear()),
        );

        $mock = Mock(BackupService::class);
        $mock->shouldReceive('all')->once()->andReturn(collect([$testSummary]));

        $testObj = new BackupStatusService($mock);
        $res = $testObj->status();

        $this->assertInstanceOf(BackupStatus::class, $res);
        $this->assertFalse($res->healthy);
        $this->assertEquals('The most recent backup is older than expected.', $res->message);
        $this->assertEquals(1, $res->backupCount);
        $this->assertInstanceOf(BackupSummary::class, $res->latest);
    }
}
