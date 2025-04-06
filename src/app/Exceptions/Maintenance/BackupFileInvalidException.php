<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

class BackupFileInvalidException extends Exception
{
    public function report(): void
    {
        Log::alert(
            'Selected file is not a valid Tech Bench backup file - '
                .$this->getMessage()
        );
    }

    public function render(): never
    {
        abort(404, 'Unable to find Backup File');
    }
}
