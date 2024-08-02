<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

class BackupFileMissingException extends Exception
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
