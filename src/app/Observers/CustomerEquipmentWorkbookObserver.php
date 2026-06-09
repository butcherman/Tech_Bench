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

    /**
     * Handle the CustomerEquipmentWorkbook "updated" event.
     */
    public function updated(CustomerEquipmentWorkbook $customerEquipmentWorkbook): void
    {
        //
    }

    /**
     * Handle the CustomerEquipmentWorkbook "deleted" event.
     */
    public function deleted(CustomerEquipmentWorkbook $customerEquipmentWorkbook): void
    {
        //
    }

    /**
     * Handle the CustomerEquipmentWorkbook "restored" event.
     */
    public function restored(CustomerEquipmentWorkbook $customerEquipmentWorkbook): void
    {
        //
    }

    /**
     * Handle the CustomerEquipmentWorkbook "force deleted" event.
     */
    public function forceDeleted(CustomerEquipmentWorkbook $customerEquipmentWorkbook): void
    {
        //
    }
}
