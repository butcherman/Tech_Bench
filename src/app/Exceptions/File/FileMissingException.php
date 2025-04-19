<?php

namespace App\Exceptions\File;

use App\Models\FileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception notes that a file is missing from the file system.  Its data
| exists in the database, but the path does not contain the expected file.
|-------------------------------------------------------------------------------
*/

class FileMissingException extends Exception
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected string $filePath)
    {
        parent::__construct();
    }

    public function report(Request $request): void
    {
        Log::error('Unable to find expected file at ' . $this->filePath);
    }

    public function render(): never
    {
        abort(404, 'The requested file was not found');
    }
}
