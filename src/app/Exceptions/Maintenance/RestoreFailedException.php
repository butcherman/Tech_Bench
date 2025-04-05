<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

class RestoreFailedException extends Exception
{
    public function report(): void
    {
        Log::critical("message");
        (
            'Backup Restore Process Failed - '
            . $this->getMessage()
        );
    }
}
