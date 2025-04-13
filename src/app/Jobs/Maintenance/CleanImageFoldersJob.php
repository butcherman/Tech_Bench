<?php

namespace App\Jobs\Maintenance;

use App\Actions\Maintenance\CleanImageFolders;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Check image folders for images no longer in use.  Automatically delete any
| orphaned images.
|-------------------------------------------------------------------------------
*/

class CleanImageFoldersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(CleanImageFolders $svc): void
    {
        Log::info('Image Folder Cleanup Job starting');

        $res = $svc(true);

        Log::info('Image Folder Cleanup Job finished. '.$res.' images deleted');
    }
}
