<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception notes that a Unique Value entry database error has occured.
|-------------------------------------------------------------------------------
*/

class DuplicateDataException extends Exception
{
    public function report(): void
    {
        Log::error('Duplicate Data Exception', [
            'message' => $this->getMessage(),
            'query_exception' => $this->getPrevious()->getMessage(),
        ]);
    }

    public function render(): RedirectResponse
    {
        return back()->withErrors(['query_error' => $this->getMessage()]);
    }
}
