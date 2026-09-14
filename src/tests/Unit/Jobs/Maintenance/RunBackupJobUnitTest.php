<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Actions\Maintenance\RunBackup;
use App\Enums\BackupType;
use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Queue;
use Mockery;
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
        $type = BackupType::Scheduled;

        $backup = Mockery::mock(RunBackup::class);

        $backup
            ->shouldReceive('handle')
            ->once()
            ->with($type);

        $job = new RunBackupJob($type);

        $job->handle($backup);
    }

    public function test_handle_uses_overlapping_middleware(): void
    {
        $job = new RunBackupJob(BackupType::Scheduled);

        $middleware = $job->middleware();

        $this->assertCount(1, $middleware);
        $this->assertInstanceOf(WithoutOverlapping::class, $middleware[0]);
    }

    public function test_handle_dispatches_properly(): void
    {
        Queue::fake();

        RunBackupJob::dispatch(BackupType::Scheduled);

        Queue::assertPushed(
            RunBackupJob::class,
            function (RunBackupJob $job) {
                return $job->type === BackupType::Scheduled
                    && $job->queue === 'backups';
            }
        );
    }
}
