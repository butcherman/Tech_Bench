<?php

namespace App\Listeners\Admin;

use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use PragmaRX\Version\Package\Version;

class LogSuccessfulBackup
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
    public function handle(BackupWasSuccessful $event): void
    {
        //
    }
}
