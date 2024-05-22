<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogFileMissingException extends Exception
{
    public function __construct(protected string $badLogFile)
    {
        parent::__construct();
    }

    public function report(Request $request)
    {
        Log::error('User ' . $request->user()->username .
            ' tried to access invalid log file - ' . $this->badLogFile);
    }

    public function render()
    {
        abort(404, 'Invalid Log File Name');
    }
}
