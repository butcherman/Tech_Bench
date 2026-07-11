<?php

namespace App\Observers;

use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentWorkbookObserver extends Observer
{
    // TODO - Fill events
    /**
     * Handle the CustomerEquipmentWorkbook "created" event.
     */
    public function created(CustomerEquipmentWorkbook $customerEquipmentWorkbook): void
    {
        Log::info('New Customer Equipment Workbook created', $customerEquipmentWorkbook->toArray());
    }
}
