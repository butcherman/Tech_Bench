<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;
use Nwidart\Modules\Facades\Module;

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
     * Attach to backup
     * Also include the Modules_Statuses.json file to show any attached modules
     */
    public function handle($event)
    {
        File::put(base_path().DIRECTORY_SEPARATOR.'version.txt', (new Version)->version_only());
        if(File::missing(base_path().DIRECTORY_SEPARATOR.'modules_statuses.json'))
        {
            File::put(base_path().DIRECTORY_SEPARATOR.'modules_statuses.json', '[]');
        }

        $event->manifest->addFiles([
            base_path().DIRECTORY_SEPARATOR.'version.txt',
            base_path().DIRECTORY_SEPARATOR.'modules_statuses.json',
            base_path().DIRECTORY_SEPARATOR.'.env',
        ]);
    }
}
