<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidMultiFactorAuthException extends Exception
{
    public function report()
    {
        Log::error($this->message);
    }

    public function render()
    {
        abort(500, $this->message);
    }
}
