<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception notes that someone tried to visit an invalid User Initialization
| link.  Attempt is logged and 404 page is shown.
|-------------------------------------------------------------------------------
*/

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
