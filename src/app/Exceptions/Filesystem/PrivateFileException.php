<?php

namespace App\Exceptions\Filesystem;

use App\Models\FileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrivateFileException extends Exception
{
    public function __construct(protected FileUpload $fileData)
    {
        parent::__construct();
    }

    public function report(Request $request)
    {
        Log::alert('A non-user is trying to download a private file', [
            'file' => $this->fileData->toArray(),
            'ip_address' => $request->ip(),
            'request_data' => $request->toArray(),
        ]);
    }

    public function render()
    {
        abort(403, 'You do not have permission to download this file');
    }
}