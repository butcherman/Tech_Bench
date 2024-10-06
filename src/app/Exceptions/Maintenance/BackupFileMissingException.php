<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Exception is triggered when a System Backup file is missing from the file system
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
