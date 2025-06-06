<?php

namespace App\Exceptions\Init;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidUserAccessingSetupException extends Exception
{
    public function report(): void
    {
        Log::critical(
            'An unauthorized user tried to gain access to the First Time Setup Wizard',
            request()->user()->toArray()
        );
    }

    public function render(): never
    {
        abort(403);
    }
}
