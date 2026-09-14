<?php

namespace Tests\Unit\Services\Maintenance;

use App\DTO\Maintenance\BackupStatus;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use App\Services\Maintenance\BackupStatusService;
use Carbon\Carbon;
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
        BackupRun::factory()->count(9)->create();

        $testObj = new BackupStatusService(new BackupService);
        $res = $testObj->status();

        $this->assertInstanceOf(BackupStatus::class, $res);
        $this->assertTrue($res->healthy);
        $this->assertNull($res->message);
        $this->assertEquals(9, $res->backupCount);
        $this->assertInstanceOf(BackupRun::class, $res->latest);
    }

    public function test_status_unhealthy(): void
    {
        BackupRun::factory()->create([
            'started_at' => Carbon::now()->subYear(),
        ]);

        $testObj = new BackupStatusService(new BackupService);
        $res = $testObj->status();

        $this->assertInstanceOf(BackupStatus::class, $res);
        $this->assertFalse($res->healthy);
        $this->assertEquals('The most recent backup is older than expected.', $res->message);
        $this->assertEquals(1, $res->backupCount);
        $this->assertInstanceOf(BackupRun::class, $res->latest);
    }
}
