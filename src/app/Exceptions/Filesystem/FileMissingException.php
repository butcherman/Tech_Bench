<?php

namespace App\Exceptions\Filesystem;

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

    public function report(Request $request)
    {
        Log::error('File Missing Exception found', [
            'file' => $this->fileData->toArray(),
            'user' => $request->user() ? $request->user()->toArray() : null,
            'ip_address' => $request->ip(),
        ]);
    }

    public function render(Request $request)
    {
        // TODO - the message does not show with Laravel Error page
        abort(404, 'The requested file was not found');
    }
}
