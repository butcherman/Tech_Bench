<?php

namespace App\Observers;

use App\Events\File\FileDataDeletedEvent;
use App\Models\FileLink;
use Illuminate\Support\Facades\Log;

class FileLinkObserver extends Observer
{
    /**
     * Handle the FileLink "created" event.
     */
    public function created(FileLink $fileLink): void
    {
        // dispatch(new HandleLinkFilesJob($fileLink));

        Log::info(
            'New File Link created by ' . $this->user,
            $fileLink->toArray()
        );
    }

    /**
     * Handle the FileLink "updated" event.
     */
    public function updated(FileLink $fileLink): void
    {
        Log::info(
            'File Link Information updated by ' . $this->user,
            $fileLink->toArray()
        );
    }

    /**
     * Before a File Link is deleted, we queue all files for deletion
     */
    public function deleting(FileLink $fileLink): void
    {
        $fileList = $fileLink->Files;

        foreach ($fileList as $file) {
            FileDataDeletedEvent::dispatch($file->file_id);
        }
    }

    /**
     * Handle the FileLink "deleted" event.
     */
    public function deleted(FileLink $fileLink): void
    {
        Log::info(
            'File Link deleted by ' . $this->user,
            $fileLink->toArray()
        );
    }
}
