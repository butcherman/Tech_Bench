<?php

namespace App\Listeners\Admin\Backup;

use App\Events\Admin\Backup\BroadcastBackupStatus;
use Illuminate\Support\Facades\File;
use PragmaRX\Version\Package\Version;
use Spatie\Backup\Events\BackupManifestWasCreated;

class AddVersionToBackup
{
    /**
     * Handle the event.
     */
    public function handle(BackupManifestWasCreated $event): void
    {
        File::put(base_path('version.txt'), (new Version)->version_only());

        $event->manifest->addFiles([base_path('version.txt')]);

        event(new BroadcastBackupStatus('Created Backup Manifest'));
    }
}
