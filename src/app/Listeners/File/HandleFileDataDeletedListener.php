<?php

namespace App\Listeners\File;

use App\Events\File\FileDataDeletedEvent;
use App\Jobs\File\DeleteFileDataJob;
use App\Models\FileUpload;

class HandleFileDataDeletedListener
{
    /**
     * Handle the event.
     */
    public function handle(FileDataDeletedEvent $event): void
    {
        $fileData = FileUpload::find($event->fileId);

        // Delay the deletion of the file to allow any long process to finish
        DeleteFileDataJob::dispatch($fileData)->delay(now()->addMinutes(5));
    }
}
