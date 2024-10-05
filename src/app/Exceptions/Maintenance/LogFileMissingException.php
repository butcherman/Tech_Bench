<?php

// TODO - Refactor

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Exception is triggered when the selected log file is missing from filesystem
 */
class LogFileMissingException extends Exception
{
    public function __construct(protected string $badLogFile)
    {
        parent::__construct();
    }

    public function report(Request $request): void
    {
        Log::error('User '.$request->user()->username.
            ' tried to access invalid log file - '.$this->badLogFile);
    }

    public function render(): never
    {
        abort(404, 'Invalid Log File Name');
    }
}
