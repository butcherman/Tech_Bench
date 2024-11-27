<?php

namespace App\Listeners\File;

use App\Actions\File\DeleteFileData;
use App\Events\File\FileUploadDeletedEvent;
use App\Models\FileUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class HandleFileUploadedDeletedListener implements ShouldQueue
{
    /**
     * Listener will try to delete the file upload from the database.  If
     * successful, the file itself will also be deleted.  Throws an exception
     * if the file is still in use elsewhere in the DB.
     */
    public function handle(FileUploadDeletedEvent $event, DeleteFileData $svc): void
    {
        if (is_array($event->fileData)) {
            Log::debug('Attempting to delete multiple files', $event->fileData);

            foreach ($event->fileData as $deleteMe) {
                $svc(FileUpload::find($deleteMe));
            }
        } else {
            Log::debug('Attempting to delete File ID '.$event->fileData);

            $svc(FileUpload::find($event->fileData));
        }
    }
}
