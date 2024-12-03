<?php

namespace App\Actions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GarbageCollection
{
    /*
    |---------------------------------------------------------------------------
    | Garbage Collection will perform the following tasks:
    |   - Remove failed jobs longer than 48 hours old.
    |   - Retry all other failed jobs.
    |   - Remove any leftover file chunks.
    |   - Prune Models.
    |---------------------------------------------------------------------------
    */
    public function __invoke(): void
    {
        Log::notice('Garbage Collection Started');

        $this->checkForFailedJobs();
        $this->cleanupChunkFolder();

        // Prune outdated models
        Artisan::call('model:prune');

        Log::notice('Garbage Collection Completed');
    }

    /**
     * Retry and Prune Failed jobs
     */
    protected function checkForFailedJobs(): void
    {
        // Delete any failed jobs more than 48 hours old
        Artisan::call('queue:prune-failed');

        // Retry any other failed jobs
        Artisan::call('queue:retry all');
    }

    /**
     * Delete any leftover file chunks from canceled uploads
     */
    protected function cleanupChunkFolder(): void
    {
        $fileList = Storage::files('chunks');

        if (count($fileList)) {
            Log::info('Found '.count($fileList).' leftover file Chunks');

            try {
                Log::info('Deleting File chunks');
                Storage::deleteDirectory('chunks');
            } catch (Exception $e) {
                report($e);
            }
        }
    }
}
