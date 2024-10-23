<?php

namespace App\Observers;

use App\Models\FileLinkFile;
use Illuminate\Support\Facades\Log;

class FileLinkFileObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

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
