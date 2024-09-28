<?php

namespace App\Listeners\Maintenance;

use Illuminate\Support\Facades\File;
use PragmaRX\Version\Package\Version;
use Spatie\Backup\Events\BackupManifestWasCreated;

class AddVersionToBackup
{
    /**
     * Create a file called 'version.txt' and put the Tech Bench's current
     * working version in it to include with the backup
     */
    public function handle(BackupManifestWasCreated $event): void
    {
        File::put(base_path().DIRECTORY_SEPARATOR.'version.txt', (new Version)->version_only());

        $event->manifest->addFiles([
            base_path().DIRECTORY_SEPARATOR.'version.txt',
            base_path().DIRECTORY_SEPARATOR.'.env',
        ]);
    }
}
