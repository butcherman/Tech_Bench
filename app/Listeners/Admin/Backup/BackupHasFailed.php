<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupHasFailed as BackupHasFailedEvent;

class BackupHasFailed
{
    /**
     * Handle the event.
     */
    public function handle(BackupHasFailedEvent $event): void
    {
        Log::critical('Backup Process failed.  Please see above Stack Trace for more information');

        event(new BroadcastBackupStatus('Backup Has Failed.  Please check logs for more information'));
    }
}
