<?php

namespace App\Exceptions\File;

use Exception;
use Illuminate\Support\Facades\Log;

class FileChunkMissingException extends Exception
{
    /*
    |---------------------------------------------------------------------------
    | Exception notes that a Chunked Upload did not include a portion of a file.
    |---------------------------------------------------------------------------
    */
    public function report()
    {
        Log::error(
            'File Upload failed, File Chunk not in request data',
            request()->toArray()
        );
    }
}
