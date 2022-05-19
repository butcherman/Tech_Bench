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
     * Create the event listener
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a file called 'version.txt' and put the Tech Bench's current working version in it
     * Attache to backup
     */
    public function handle($event)
    {
        File::put(base_path().DIRECTORY_SEPARATOR.'version.txt', (new Version)->version_only());
        $event->manifest->addFiles([base_path().DIRECTORY_SEPARATOR.'version.txt']);
    }
}
