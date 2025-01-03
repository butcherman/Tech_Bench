<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * Generic exception created for when write to database fails.  Generic
 * response is returned, while additional data is logged for admin.
 *
 * @codeCoverageIgnore
 */
class GeneralQueryException extends Exception
{
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
