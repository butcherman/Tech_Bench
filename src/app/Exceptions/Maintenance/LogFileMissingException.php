<?php

namespace App\Exceptions\Maintenance;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class LogFileMissingException extends Exception
{
    public function __construct(protected string $badLogFile)
    {
        parent::__construct();
    }

    public function report(Request $request): void
    {
        Log::error('User ' . $request->user()->username .
            ' tried to access invalid log file - ' . $this->badLogFile);
    }

    public function render(): never
    {
        abort(404, 'Invalid Log File');
    }
}
