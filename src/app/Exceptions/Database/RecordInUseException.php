<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * Exception triggered when database query fails due to foreign key
 * constraint.
 */
class RecordInUseException extends Exception
{
    public function report(): void
    {
        Log::alert('Record In Use Exception', [
            'message' => $this->getMessage(),
            'query_exception' => $this->getPrevious()->getMessage(),
        ]);
    }

    public function render(): RedirectResponse
    {
        return back()->withErrors(['query_error' => $this->getMessage()]);
    }
}
