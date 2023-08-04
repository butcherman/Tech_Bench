<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupZipWasCreated as BackupZipWasCreatedEvent;

class BackupZipWasCreated
{
    /**
     * Handle the event.
     */
    public function handle(BackupZipWasCreatedEvent $event): void
    {
        Log::debug('Backup Zip was created');

        event(new BroadcastBackupStatus('Backup Zip was created'));
    }
}
