<?php

namespace App\Exceptions\Customer;

use Exception;
use Illuminate\Support\Facades\Log;

class WorkbookNotPublishedException extends Exception
{
    public function report(): void
    {
        Log::notice(
            'User trying to access unpublished workbook',
            request()->toArray()
        );
    }

    public function render(): never
    {
        abort(404);
    }
}
