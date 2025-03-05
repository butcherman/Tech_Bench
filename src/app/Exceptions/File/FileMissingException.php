<?php

namespace App\Exceptions\File;

use App\Models\FileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileMissingException extends Exception
{
    public function __construct(protected FileUpload $fileData)
    {
        parent::__construct();
    }

    /*
    |---------------------------------------------------------------------------
    | Exception notes that a file is missing from the file system.  Its data
    | exists in the database, but the path does not contain the expected file.
    |---------------------------------------------------------------------------
    */
    public function report(Request $request): void
    {
        Log::error('File Missing Exception found', [
            'file' => $this->fileData->toArray(),
            'user' => $request->user() ? $request->user()->toArray() : null,
            'ip_address' => $request->ip(),
        ]);
    }

    public function render(): never
    {
        abort(404, 'The requested file was not found');
    }
}
