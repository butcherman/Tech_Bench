<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception is triggered when a selected System Backup file is does ot exist
| in the application filesystem.
|-------------------------------------------------------------------------------
*/

class BackupFileMissingException extends Exception
{
    public function report(): void
    {
        Log::alert('Backup file missing - '.$this->getMessage());
    }

    public function render(): never
    {
        abort(404, 'Unable to find Backup File');
    }
}
