<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class RecordInUseException extends Exception
{
    public function report()
    {
        Log::alert($this->getMessage());
        Log::alert($this->getPrevious()->getMessage());
    }

    public function render()
    {
        return back()->withErrors(['query_error' => $this->getMessage()]);
    }
}
