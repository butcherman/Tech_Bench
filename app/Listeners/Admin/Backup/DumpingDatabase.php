<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Spatie\Backup\Events\DumpingDatabase as DumpingDatabaseEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
