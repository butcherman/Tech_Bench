<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Actions\Maintenance\RestoreBackup;
use App\Jobs\Maintenance\RestoreBackupJob;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class RestoreBackupJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $backup = Mockery::mock(RestoreBackup::class);

        $backup
            ->shouldReceive('__invoke')
            ->once()
            ->with('test-backup');

        $job = new RestoreBackupJob('test-backup');

        $job->handle($backup);

        $this->assertTrue(true);
    }

    public function test_handle_uses_overlapping_middleware(): void
    {
        $job = new RestoreBackupJob('test-backup');

        $middleware = $job->middleware();

        $this->assertCount(1, $middleware);
        $this->assertInstanceOf(WithoutOverlapping::class, $middleware[0]);
    }

    public function test_handle_dispatches_properly(): void
    {
        Queue::fake();

        RestoreBackupJob::dispatch('test-backup');

        Queue::assertPushed(
            RestoreBackupJob::class,
            function (RestoreBackupJob $job) {
                return $job->queue === 'backups';
            }
        );
    }
}
