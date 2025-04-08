<?php

namespace App\Jobs\Maintenance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GarbageCollectionJob implements ShouldQueue
{
    use Queueable;

    /**
     * Job is placed on the backups queue which only has one process.  This is
     * done so that backups and garbage collection do not interfere with
     * each other.
     */
    public function __construct()
    {
        $this->onQueue('backups');
    }

    /**
     * Call daily maintenance commands.
     */
    public function handle(): void
    {
        Log::info('Garbage Collection Job starting');

        // Determine if any backup files need to be removed
        if (config('backup.nightly_cleanup')) {
            Artisan::call('backup:cleanup');
        }

        // Prune failed jobs more than 48 hours old and retry all others
        Artisan::call('queue:prune-failed');
        Artisan::call('queue:retry all');

        // Clear out the Chunk folder removing any orphaned upload chunks
        Storage::deleteDirectory('chunks');

        Log::info('Garbage Collection Job Finished');
    }
}
