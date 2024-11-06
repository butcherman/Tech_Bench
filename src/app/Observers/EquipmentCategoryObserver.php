<?php

namespace App\Observers;

use App\Facades\CacheFacade;
use App\Models\EquipmentCategory;
use Illuminate\Support\Facades\Log;

class EquipmentCategoryObserver extends Observer
{
    /**
     * Handle the EquipmentCategory "created" event.
     */
    public function created(EquipmentCategory $equipmentCategory): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');

        Log::info(
            'New Equipment Category created by '.$this->user,
            $equipmentCategory->toArray()
        );
    }

    /**
     * Handle the EquipmentCategory "updated" event.
     */
    public function updated(EquipmentCategory $equipmentCategory): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');

        Log::info(
            'Equipment Category updated by '.$this->user,
            $equipmentCategory->toArray()
        );
    }

    /**
     * Handle the EquipmentCategory "deleted" event.
     */
    public function deleted(EquipmentCategory $equipmentCategory): void
    {
        CacheFacade::clearCache('equipmentTypes');
        CacheFacade::clearCache('equipmentCategories');

        Log::info(
            'Equipment Category deleted by '.$this->user,
            $equipmentCategory->toArray()
        );
    }
}
