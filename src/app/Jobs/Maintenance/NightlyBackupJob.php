<?php

namespace App\Jobs\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupHasFailed;

class NightlyBackupJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Backups are only allowed on the backup queue.
     */
    public function __construct()
    {
        $this->onQueue('backups');
    }

    /**
     * Lock job so that only one backup can be running at a time.
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('backup_process')),
        ];
    }

    /**
     * Run a system backup if nightly backups are enabled.
     */
    public function handle(BackupService $svc): void
    {
        if (! config('backup.nightly_backup')) {
            Log::info('Nightly Backup job feature disable.  Backup not run');

            return;
        }

        Log::info('Starting Manual Backup');

        if (! $svc->verifyBackupDiskSpace()) {
            $exception = new BackupFailedException('Not enough free space to run backup');
            event(new BackupHasFailed($exception));

            throw $exception;
        }

        Artisan::call('backup:run', [], new ConsoleOutputService);
    }
}
