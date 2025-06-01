<?php

namespace App\Exceptions\Init;

use Exception;
use Illuminate\Support\Facades\Log;

class FirstTimeSetupAlreadyCompletedException extends Exception
{
    public function report(): void
    {
        Log::error(
            request()->user()->username .
                ' is trying to access the setup wizard when the app is already setup'
        );
    }

    public function render(): never
    {
        abort(403, 'The First Time Setup can only be run once');
    }
}
