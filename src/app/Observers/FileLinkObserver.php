<?php

namespace App\Observers;

use App\Events\File\FileDataDeletedEvent;
use App\Models\FileLink;
use Illuminate\Support\Facades\Log;

class FileLinkObserver
{
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            request()->user() !== null => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            default => request()->ip(),
        };
    }

    /**
     * Handle the FileLink "created" event.
     */
    public function created(FileLink $fileLink): void
    {
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
    public function deleting(FileLink $fileLink)
    {
        $fileList = $fileLink->FileUpload->pluck('file_id')->toArray();

        foreach ($fileList as $fileId) {
            event(new FileDataDeletedEvent($fileId));
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
