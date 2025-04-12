<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception is triggered when a backup restore process fails for any reason.
|-------------------------------------------------------------------------------
*/

class RestoreFailedException extends Exception
{
    public function report(): void
    {
        Log::critical('message');
        (
            'Backup Restore Process Failed - '
            .$this->getMessage()
        );
    }
}
