<?php

namespace App\Jobs\Maintenance;

use App\Actions\Maintenance\RestoreBackup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class RestoreBackupJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $backupName)
    {
        $this->onQueue('backups');
    }

    /**
     * Multiple backup/restore processes cannot run at the same time.
     */
    public function middleware(): array
    {
        return [
            new WithoutOverlapping('backup_process')
                ->dontRelease()
                ->expireAfter(600),
        ];
    }

    /**
     * Restore the system from a backup
     */
    public function handle(RestoreBackup $action): void
    {
        $action->__invoke($this->backupName);
    }
}
