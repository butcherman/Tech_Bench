<?php

namespace App\Jobs\Maintenance;

use App\Actions\Maintenance\RunDatabaseBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

/**
 * Triggered by manual backup command
 */
class RunBackupJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('backups');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Artisan::call('backup:run');
        new RunDatabaseBackup;
    }
}
