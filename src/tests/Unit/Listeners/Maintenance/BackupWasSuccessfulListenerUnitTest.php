<?php

namespace Tests\Unit\Listeners\Maintenance;

use App\DTO\Maintenance\BackupSummary;
use App\Enums\BackupRunStatus;
use App\Enums\BackupType;
use App\Listeners\Maintenance\BackupWasSuccessfulListener;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use Spatie\Backup\Events\BackupWasSuccessful;
use Tests\TestCase;

class BackupWasSuccessfulListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_active_backup(): void
    {
        $backup = BackupRun::factory()->create([
            'type' => BackupType::Cli,
            'status' => BackupRunStatus::Running,
        ]);

        $testObj = new BackupWasSuccessfulListener(new BackupService);
        $testObj->handle(new BackupWasSuccessful('backups', 'test.zip'));

        $this->assertDatabaseHas('backup_runs', [
            'backup_id' => $backup->backup_id,
            'type' => 'cli',
            'status' => 'running',
        ]);
    }

    public function test_handle_multiple_active_backups(): void
    {
        BackupRun::factory()
            ->count(3)
            ->create(['status' => BackupRunStatus::Running]);

        $backup = BackupRun::factory()->create([
            'type' => BackupType::Cli,
            'status' => BackupRunStatus::Running,
        ]);

        Log::shouldReceive('critical')
            ->once();

        $testObj = new BackupWasSuccessfulListener(new BackupService);
        $testObj->handle(new BackupWasSuccessful('backups', 'test.zip'));

        $this->assertDatabaseHas('backup_runs', [
            'backup_id' => $backup->backup_id,
            'type' => 'cli',
            'status' => 'running',
        ]);
    }

    public function test_handle_no_known_backup_was_running(): void
    {
        $svcMock = $this->mock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldReceive('latestFile')
                ->once()
                ->andReturn(new BackupSummary(
                    name: 'test.zip',
                    size: 6,
                    modified: new CarbonImmutable
                ));
        });

        $testObj = new BackupWasSuccessfulListener($svcMock);
        $testObj->handle(new BackupWasSuccessful('backups', 'test.zip'));

        $this->assertDatabaseHas('backup_runs', [
            'type' => 'unknown',
            'status' => 'completed',
        ]);
    }
}
