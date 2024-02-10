<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RecordInUseException extends Exception
{
    // public function __construct(protected QueryException $exception, protected string $msg)
    // {
    //     parent::__construct($msg);
    // }

    public function report()
    {
        // dd($this->exception);

        Log::alert($this->getMessage());
    }

    public function render()
    {
        return back()->withErrors(['query_error' => $this->getMessage()]);
    }
}
