<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Triggered when a requested backup file does not exist
 */
class BackupMissingException extends Exception
{
    public function report()
    {
        Log::alert('Backup file missing - '.$this->getMessage());
    }

    public function render()
    {
        abort(404, 'Unable to find Backup File');
    }
}
