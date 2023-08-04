<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class LogFileMissingException extends Exception
{
    public function __construct(public string $logFile)
    {

    }
    public function report()
    {
        Log::alert('Selected log file '.$this->logFile.' is missing from filesystem');
    }

    public function render()
    {
        abort(404, 'Unable to find the selected Log File');
    }
}
