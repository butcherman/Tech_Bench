<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulBackup
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
        Log::info('Backup process was successful', [
            'destination' => $event->backupDestination,
        ]);
    }
}
