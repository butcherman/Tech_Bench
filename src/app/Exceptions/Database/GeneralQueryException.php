<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * @codeCoverageIgnore
 */
class GeneralQueryException extends Exception
{
    public function report()
    {
        Log::critical('Update of Database Record failed', [
            'message' => $this->getPrevious()->getMessage(),
        ]);
    }

    public function render()
    {
        return back()->withErrors([
            'query_error' => 'Unable to update Database Record, see logs for more info.',
        ]);
    }
}