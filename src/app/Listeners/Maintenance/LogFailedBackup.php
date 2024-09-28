<?php

namespace App\Listeners\Maintenance;

use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupHasFailed;

class LogFailedBackup
{
    /**
     * Handle the event.
     */
    public function handle(BackupHasFailed $event): void
    {
        Log::critical('Backup process failed', [
            'message' => $event->exception->getMessage(),
            'destination' => $event->backupDestination,
        ]);
    }
}
