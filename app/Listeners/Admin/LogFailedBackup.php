<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogFailedBackup
{
    /**
     * Create the event listener
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event
     */
    public function handle($event)
    {
        Log::critical('Backup process failed', [
            'message'     => $event->exception->getMessage(),
            'destination' => $event->backupDestination,
        ]);
    }
}
