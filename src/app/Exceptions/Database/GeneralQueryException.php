<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * @codeCoverageIgnore
 */
class GeneralQueryException extends Exception
{
    /*
    |---------------------------------------------------------------------------
    | Exception notes a catch-all database query has occurred.  The original
    | exception will be logged along with this current exception.
    |---------------------------------------------------------------------------
    */
    public function report(): void
    {
        Log::critical('Update of Database Record failed', [
            'message' => $this->getPrevious()->getMessage(),
        ]);
    }

    public function render(): RedirectResponse
    {
        return back()->withErrors([
            'query_error' => 'Unable to update Database Record, see logs for more info.',
        ]);
    }
}
