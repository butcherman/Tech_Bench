<?php

namespace App\Jobs\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Laravel\Prompts\Output\ConsoleOutput;

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

        $this->checkDiskSpace();
        Artisan::call('backup:run', [], new ConsoleOutput);
    }

    /**
     * Calculate the size of the storage path vs available space
     */
    protected function checkDiskSpace()
    {
        $backupFreeSpace = disk_free_space(storage_path('/backups'));
        $storageProcess = Process::run('du -s '.storage_path());
        preg_match('/\d*/', $storageProcess->output(), $storageSize);

        Log::debug('Checking Disk Space before running backup', [
            'free_space_in_backup_disk' => $backupFreeSpace,
            'size_of_storage_folder' => $storageSize[0],
        ]);

        if ($backupFreeSpace <= $storageSize[0] - 500) {
            throw new BackupFailedException('Not enough available disk space');
        }

        return true;
    }
}
