<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RecordInUseException extends Exception
{
    /*
    |---------------------------------------------------------------------------
    | Exception notes that a database record cannot be deleted because its key
    | still exists somewhere in the database as a Foreign Key.
    |---------------------------------------------------------------------------
    */
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
