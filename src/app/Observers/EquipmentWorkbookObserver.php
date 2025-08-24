<?php

namespace App\Observers;

use App\Models\EquipmentWorkbook;
use Illuminate\Support\Facades\Log;

class EquipmentWorkbookObserver extends Observer
{
    /**
     * Handle the EquipmentWorkbook "created" event.
     */
    public function created(EquipmentWorkbook $equipmentWorkbook): void
    {
        Log::info(
            'Equipment Workbook created for ' .
            $equipmentWorkbook->EquipmentType->name . ' by ' . $this->user
        );
    }

    /**
     * Handle the EquipmentWorkbook "updated" event.
     */
    public function updated(EquipmentWorkbook $equipmentWorkbook): void
    {
        Log::info(
            'Equipment Workbook updated for ' .
            $equipmentWorkbook->EquipmentType->name . ' by ' . $this->user
        );
    }

    /**
     * Handle the EquipmentWorkbook "deleted" event.
     */
    public function deleted(EquipmentWorkbook $equipmentWorkbook): void
    {
        //
    }
}
