<?php

namespace App\Listeners\Admin;

use Spatie\Backup\Events\BackupManifestWasCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use PragmaRX\Version\Package\Version;

class AddVersionToBackup
{
    /**
     * Handle the event.
     */
    public function handle(BackupManifestWasCreated $event): void
    {
        File::put(base_path('version.txt'), (new Version)->version_only());

        $event->manifest->addFiles([
            base_path('version.txt'),
        ]);
    }
}
