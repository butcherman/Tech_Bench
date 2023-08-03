<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Spatie\Backup\Events\BackupHasFailed as BackupHasFailedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BackupHasFailed
{
    /**
     * Handle the event.
     */
    public function handle(BackupHasFailedEvent $event): void
    {
        Log::critical('Backup Process failed.', $event);

        event(new BroadcastBackupStatus('Backup Has Failed.  Please check logs for more information'));
    }
}
