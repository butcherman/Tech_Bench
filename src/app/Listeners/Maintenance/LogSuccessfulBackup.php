<?php

namespace App\Listeners\Maintenance;

use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupWasSuccessful;

class LogSuccessfulBackup
{
    /**
     * Handle the event.
     */
    public function handle(BackupWasSuccessful $event): void
    {
        Log::info('Backup process was successful', [
            'destination' => $event->backupDestination,
        ]);
    }
}
