<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidLogChannelException extends Exception
{
    public function report(): void
    {
        Log::notice('Invalid Log Channel has been accessed', request()->toArray());
    }

    public function render(): never
    {
        abort(404);
    }
}
