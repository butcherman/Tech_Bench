<?php

namespace App\Listeners\Admin;

use App\Events\Spatie\Backup\Events\BackupHasFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedBackup
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BackupHasFailed $event): void
    {
        //
    }
}
