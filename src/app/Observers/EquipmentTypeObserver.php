<?php

namespace App\Observers;

use App\Facades\CacheFacade;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Log;

class EquipmentTypeObserver extends Observer
{
    /**
     * Handle the EquipmentType "created" event.
     */
    public function created(EquipmentType $equipmentType): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');
        CacheFacade::clearCache('publicEquipmentCategories');

        Log::info(
            'New Equipment Type created by '.$this->user,
            $equipmentType->toArray()
        );
    }

    /**
     * Handle the EquipmentType "updated" event.
     */
    public function updated(EquipmentType $equipmentType): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');
        CacheFacade::clearCache('publicEquipmentCategories');

        Log::info(
            'Equipment Type updated by '.$this->user,
            $equipmentType->toArray()
        );
    }

    /**
     * Handle the EquipmentType "deleted" event.
     */
    public function deleted(EquipmentType $equipmentType): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');
        CacheFacade::clearCache('publicEquipmentCategories');

        Log::info(
            'Equipment Type deleted by '.$this->user,
            $equipmentType->toArray()
        );
    }
}
