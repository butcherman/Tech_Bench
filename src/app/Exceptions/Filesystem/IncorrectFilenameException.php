<?php

namespace App\Exceptions\Filesystem;

use App\Models\FileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncorrectFilenameException extends Exception
{
    public function __construct(protected string $fileName, protected FileUpload $fileData)
    {
        parent::__construct();
    }

    public function report(Request $request)
    {
        Log::error('File download prevented.  Filename does not match file ID', [
            'file' => $this->fileData->toArray(),
            'passed_filename' => $this->fileName,
            'user' => $request->user() ? $request->user()->toArray : null,
            'ip_address' => $request->ip(),
        ]);
    }

    public function render()
    {
        abort(403, 'Incorrect File Data');
    }
}
