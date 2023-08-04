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
 * Scheduled Daily Backup Process
 */
class DailyBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * We will only trigger a backup if the automatic nightly backups are enabled
     */
    public function handle(): void
    {
        if (config('backup.nightly_backup')) {
            Log::info('Starting automatic backup operation');
            Artisan::call('backup:run');
        }
    }
}
