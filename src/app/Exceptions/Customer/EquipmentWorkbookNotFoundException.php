<?php

namespace App\Exceptions\Customer;

use Exception;
use Illuminate\Support\Facades\Log;

class EquipmentWorkbookNotFoundException extends Exception
{
    public function report(): void
    {
        Log::notice('Unable to Locate Equipment Workbook for Request', request()->toArray());
    }

    public function render(): never
    {
        abort(404);
    }
}
