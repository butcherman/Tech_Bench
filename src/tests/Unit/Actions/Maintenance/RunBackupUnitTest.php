<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\RunBackup;
use App\Enums\BackupRunStatus;
use App\Enums\BackupType;
use App\Exceptions\Maintenance\BackupFailedException;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class RunBackupUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $type = BackupType::Scheduled;

        Artisan::shouldReceive('call')
            ->once()
            ->withArgs(function (
                string $command,
                array $parameters,
                $output
            ): bool {
                return $command === 'backup:run'
                    && $parameters === [];
            })
            ->andReturn(0);

        $run = app(RunBackup::class)->handle($type);

        $this->assertInstanceOf(BackupRun::class, $run);

        $this->assertDatabaseHas('backup_runs', [
            'backup_id' => $run->backup_id,
            'type' => $type->value,
            'status' => BackupRunStatus::Completed->value,
        ]);

        $this->assertNotNull($run->started_at);
        $this->assertNotNull($run->completed_at);
        $this->assertNull($run->error);
    }

    public function test_handle_failed_artisan_command(): void
    {
        $type = BackupType::Scheduled;

        $service = Mockery::mock(BackupService::class);

        Artisan::shouldReceive('call')
            ->once()
            ->andReturn(1);

        $this->expectException(BackupFailedException::class);

        try {
            app(RunBackup::class)->handle($type);
        } catch (BackupFailedException $e) {
            $this->assertDatabaseHas('backup_runs', [
                'type' => $type->value,
                'status' => BackupRunStatus::Failed->value,
                'error' => 'Tech Bench backup failed with exit code 1.',
            ]);

            throw $e;
        }
    }

    public function test_handle_artisan_fails(): void
    {
        $type = BackupType::Manual;

        Artisan::shouldReceive('call')
            ->once()
            ->andThrow(new \RuntimeException('Backup command failed.'));

        $this->expectException(\RuntimeException::class);

        try {
            app(RunBackup::class)->handle($type);
        } catch (\RuntimeException $e) {
            $this->assertDatabaseHas('backup_runs', [
                'type' => $type->value,
                'status' => BackupRunStatus::Failed->value,
                'error' => 'Backup command failed.',
            ]);

            throw $e;
        }
    }
}
