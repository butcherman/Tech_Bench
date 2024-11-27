<?php

namespace App\Events\File;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | Event is called when a File Upload foreign key is deleted from a child
    | table.  The Listener will verify the file is no longer in use and
    | remove the file from the database and storage system if it is
    | no longer needed.
    |---------------------------------------------------------------------------
    */
    public function __construct(public string|array $fileData) {}
}
