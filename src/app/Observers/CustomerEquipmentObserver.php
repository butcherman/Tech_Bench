<?php

namespace App\Observers;

use App\Models\CustomerEquipment;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentObserver extends Observer
{
    /**
     * Handle the CustomerEquipment "created" event.
     */
    public function created(CustomerEquipment $customerEquipment): void
    {
        Log::info(
            'New Equipment added to '.$customerEquipment->Customer->name.
                ' by '.$this->user,
            $customerEquipment->toArray()
        );
    }

    /**
     * Handle the CustomerEquipment "updated" event.
     */
    public function updated(CustomerEquipment $customerEquipment): void
    {
        Log::info(
            'Equipment updated for '.$customerEquipment->Customer->name.
                ' by '.$this->user,
            $customerEquipment->toArray()
        );
    }

    /**
     * Handle the CustomerEquipment "deleted" event.
     */
    public function deleted(CustomerEquipment $customerEquipment): void
    {
        Log::info(
            'Equipment deleted for '.$customerEquipment->Customer->name.
                ' by '.$this->user,
            $customerEquipment->toArray()
        );
    }

    /**
     * Handle the CustomerEquipment "restored" event.
     */
    public function restored(CustomerEquipment $customerEquipment): void
    {
        Log::info(
            'Equipment restored for '.$customerEquipment->Customer->name.
                ' by '.$this->user,
            $customerEquipment->toArray()
        );
    }

    /**
     * Handle the CustomerEquipment "force deleted" event.
     */
    public function forceDeleted(CustomerEquipment $customerEquipment): void
    {
        Log::info(
            'Equipment trashed for '.$customerEquipment->Customer->name.
                ' by '.$this->user,
            $customerEquipment->toArray()
        );
    }
}
