<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Jobs\Maintenance\GarbageCollectionJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GarbageCollectionJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        config(['backup.nightly_cleanup' => true]);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:cleanup')
            ->andReturn(null);
        Artisan::shouldReceive('call')
            ->once()
            ->with('queue:prune-failed')
            ->andReturn(null);
        Artisan::shouldReceive('call')
            ->once()
            ->with('queue:retry all')
            ->andReturn(null);

        GarbageCollectionJob::dispatch();
    }

    public function test_handle_verify_queue(): void
    {
        Queue::fake();

        GarbageCollectionJob::dispatch();

        Queue::assertPushedOn('backups', GarbageCollectionJob::class);
    }
}
