<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception is triggered when an application backup fails.
|-------------------------------------------------------------------------------
*/

class BackupFailedException extends Exception
{
    public function report(): void
    {
        Log::error('Backup Process Failed.  Reason - '.$this->getMessage());
    }

    public function render(): RedirectResponse
    {
        return back()
            ->withErrors('Backup Failed.  Reason - '.$this->getMessage());
    }
}
