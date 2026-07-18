<?php

namespace App\Observers;

use App\Models\WorkbookTableValue;
use Illuminate\Support\Facades\Log;

class WorkbookTableValueObserver extends Observer
{
    /**
     * Handle the WorkbookTableValue "created" event.
     */
    public function created(WorkbookTableValue $workbookTableValue): void
    {
        Log::info(
            'New Workbook Table Value Saved',
            $workbookTableValue->toArray()
        );
    }

    /**
     * Handle the WorkbookTableValue "updated" event.
     */
    public function updated(WorkbookTableValue $workbookTableValue): void
    {
        Log::info(
            'Workbook Table Value Updated',
            $workbookTableValue->toArray()
        );
    }
}
