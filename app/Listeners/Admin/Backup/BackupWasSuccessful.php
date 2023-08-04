<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupWasSuccessful as BackupWasSuccessfulEvent;

class BackupWasSuccessful
{
    /**
     * Handle the event.
     */
    public function handle(BackupWasSuccessfulEvent $event): void
    {
        Log::notice('Backup Successfully Completed');

        event(new BroadcastBackupStatus('Backup was successful', true));
    }
}
