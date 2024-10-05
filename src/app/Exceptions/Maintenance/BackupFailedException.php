<?php

// TODO - Refactor

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

class BackupFailedException extends Exception
{
    public function report()
    {

        Log::error('Backup Process Failed.  Reason - '.$this->getMessage());

    }

    public function render()
    {
        return back()->withErrors('Backup Failed.  Reason - '.$this->getMessage());
    }
}
