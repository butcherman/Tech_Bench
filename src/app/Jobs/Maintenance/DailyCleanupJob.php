<?php

namespace App\Jobs\Maintenance;

use App\Service\Maint\GarbageCollectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DailyCleanupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('backups');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Determine if Backup Files are to be cleaned up
        if (config('backup.nightly_clean')) {
            Log::info('Calling backup cleanup job');
            Artisan::call('backup:cleanup');
        }

        // Run Garbage Collection Service
        new GarbageCollectionService;
    }
}
