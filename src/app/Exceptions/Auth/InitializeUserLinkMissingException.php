<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Support\Facades\Log;

class InitializeUserLinkMissingException extends Exception
{
    public function report(): void
    {
        Log::notice(
            'Someone is trying to access a user initialization link that does not exist',
            request()->toArray()
        );
    }

    public function render(): never
    {
        abort(404, 'Page Not Found');
    }
}
