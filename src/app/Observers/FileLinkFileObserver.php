<?php

namespace App\Observers;

use App\Models\FileLinkFile;
use Illuminate\Support\Facades\Log;

class FileLinkFileObserver extends Observer
{
    /**
     * Handle the FileLinkFile "created" event.
     */
    public function created(FileLinkFile $fileLinkFile): void
    {
        Log::info(
            'New File uploaded to File Link ID '.$fileLinkFile->link_id.
                ' by '.$this->user,
            $fileLinkFile->toArray()
        );
    }

    /**
     * Handle the FileLinkFile "deleted" event.
     */
    public function deleted(FileLinkFile $fileLinkFile): void
    {
        Log::info(
            'File deleted for File Link ID '.$fileLinkFile->link_id.
                ' by '.$this->user,
            $fileLinkFile->toArray()
        );
    }
}
