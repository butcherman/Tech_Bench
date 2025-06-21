<?php

namespace App\Exceptions\File;

use App\Models\FileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| When accessing a file for download, the file-id and filename must be
| provided.  If the file name stored in the database does not match the
| provided filename, the download will not be allowed.
|-------------------------------------------------------------------------------
*/

class IncorrectFilenameException extends Exception
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        protected string $fileName,
        protected FileUpload $fileData
    ) {
        parent::__construct();
    }

    public function report(Request $request): void
    {
        Log::error('File download prevented.  Filename does not match file ID', [
            'file' => $this->fileData->toArray(),
            'passed_filename' => $this->fileName,
            'user' => $request->user() ? $request->user()->toArray : null,
            'ip_address' => $request->ip(),
        ]);
    }

    public function render(): never
    {
        abort(403, 'Incorrect File Data');
    }
}
