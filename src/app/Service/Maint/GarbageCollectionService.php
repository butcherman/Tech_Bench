<?php

namespace App\Service\Maint;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GarbageCollectionService
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        Log::notice('Garbage Collection Started');
        $this->checkForFailedJobs();
        $this->cleanupChunkFolder();

        Log::notice('Garbage Collection Completed');
    }

    /**
     * Retry and Prune Failed jobs
     */
    protected function checkForFailedJobs()
    {
        // Delete any failed jobs more than 48 hours old
        Artisan::call('queue:prune-failed');

        // Retry any other failed jobs
        Artisan::call('queue:retry all');
    }

    /**
     * Delete any leftover file chunks from canceled uploads
     */
    protected function cleanupChunkFolder()
    {
        $fileList = Storage::files('chunks');

        if (count($fileList)) {
            Log::info('Found ' . count($fileList) . ' leftover file Chunks');

            try {
                Log::info('Deleting File chunks');
                Storage::deleteDirectory('chunks');
            } catch (Exception $e) {
                report($e);
            }
        }
    }
}