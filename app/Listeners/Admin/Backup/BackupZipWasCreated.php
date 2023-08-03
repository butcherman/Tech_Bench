<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Spatie\Backup\Events\BackupZipWasCreated as BackupZipWasCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
