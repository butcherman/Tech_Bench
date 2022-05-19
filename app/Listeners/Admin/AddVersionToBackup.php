<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;

class AddVersionToBackup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        // Storage::disk('backups')->put('version.txt', (new Version)->version_only());

        // Storage::disk('backups')->put('backup-temp/version.txt', (new Version)->version_only());
        // $event->manifest->addFiles([storage_path('backups/backup-temp').'/version.txt']);
        // $event->manifest->addFiles([Storage::disk('backups')->get('backup-temp/version.txt')]);

        File::put(base_path().DIRECTORY_SEPARATOR.'version.txt', (new Version)->version_only());
        $event->manifest->addFiles([base_path().DIRECTORY_SEPARATOR.'version.txt']);
    }
}
