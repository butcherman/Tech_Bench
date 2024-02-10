<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Support\Facades\Log;

class RecordInUseException extends Exception
{
    public function report()
    {
        Log::alert('Record In Use Exception', [
            'message' => $this->getMessage(),
            'query_exception' => $this->getPrevious()->getMessage(),
        ]);
    }

    public function render()
    {
        return back()->withErrors(['query_error' => $this->getMessage()]);
    }
}
