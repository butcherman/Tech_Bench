<?php

namespace App\Listeners\File;

use App\Events\File\FileDataDeletedEvent;
use App\Models\FileUpload;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFileFromDiskIfNotInUse
{
    /**
     * Handle the event.
     */
    public function handle(FileDataDeletedEvent $event): void
    {
        $fileData = FileUpload::find($event->fileId);

        try {
            $path = $fileData->folder.DIRECTORY_SEPARATOR.$fileData->file_name;

            $fileData->delete();
            Storage::disk($fileData->disk)->delete($path);

            Log::notice('File '.$path.' on disk '.$fileData->disk.' has been deleted');
        } catch (QueryException $e) {
            Log::debug(
                'Attempt to delete file failed, file is still in use',
                $fileData->toArray()
            );
        }
    }
}
