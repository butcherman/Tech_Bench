<?php

namespace App\Jobs\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupHasFailed;

class RunBackupJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * Run an application backup.
     */
    public function handle(BackupService $svc): void
    {
        Log::info('Starting Backup');

        if (! $svc->verifyBackupDiskSpace()) {
            $exception = new BackupFailedException('Not enough free space to run backup');
            event(new BackupHasFailed($exception));

            throw $exception;
        }

        Artisan::call('backup:run', [], new ConsoleOutputService);
    }
}
