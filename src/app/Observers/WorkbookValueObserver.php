<?php

namespace App\Observers;

use App\Models\WorkbookValue;
use Illuminate\Support\Facades\Log;

class WorkbookValueObserver extends Observer
{
    /**
     * Handle the WorkbookValue "created" event.
     */
    public function created(WorkbookValue $workbookValue): void
    {
        Log::info('Workbook Value created', $workbookValue->toArray());
    }

    /**
     * Handle the WorkbookValue "updated" event.
     */
    public function updated(WorkbookValue $workbookValue): void
    {
        Log::info('Workbook Value Updated', $workbookValue->toArray());
    }
}
