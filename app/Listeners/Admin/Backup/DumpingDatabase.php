<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\DumpingDatabase as DumpingDatabaseEvent;

class DumpingDatabase
{
    /**
     * Handle the event.
     */
    public function handle(DumpingDatabaseEvent $event): void
    {
        Log::debug('Dumping Database');

        event(new BroadcastBackupStatus('Dumping Database'));
    }
}
