<?php

namespace App\Listeners\File;

use App\Events\File\FileUploadDeletedEvent;
use App\Facades\DbException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HandleFileUploadedDeletedListener implements ShouldQueue
{
    /**
     * Listener will try to delete the file upload from the database.  If
     * successful, the file itself will also be deleted.  Throws an exception
     * if the file is still in use elsewhere in the DB.
     */
    public function handle(FileUploadDeletedEvent $event): void
    {
        /** @var FileUpload */
        $fileData = $event->fileUpload;

        // TODO - Move the logic to a service class
        try {
            $path = $fileData->folder.DIRECTORY_SEPARATOR.$fileData->file_name;

            $fileData->delete();
            Storage::disk($fileData->disk)->delete($path);

            Log::notice('File '.$path.' on disk '.$fileData->disk.' has been deleted');
        } catch (QueryException $e) {
            Log::error('Delete File Failed - '.$e->getMessage());
            DbException::check($e);
        }
    }
}
