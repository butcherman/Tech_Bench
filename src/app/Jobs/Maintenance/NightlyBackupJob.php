<?php

namespace App\Jobs\Maintenance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Nightly backup job.
 * Backup will only run if enabled in config
 */
class NightlyBackupJob implements ShouldQueue
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
        if (config('backup.nightly_backup')) {
            Log::info('Calling nightly backup job');
            Artisan::call('backup:run');
        }
    }
}