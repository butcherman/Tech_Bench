<?php

namespace App\Observers;

use App\Models\CustomerEquipmentData;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            default => request()->ip(),
        };
    }

    public function created(CustomerEquipmentData $data): void
    {
        //
    }

    public function updated(CustomerEquipmentData $data): void
    {
        $originalData = $data->getOriginal();

        Log::info(
            'Customer Equipment Data Updated by '.$this->user, [
                'customer_equipment_id' => $data->cust_equip_id,
                'data-field' => $data->field_name,
                'old-value' => $originalData['value'],
                'new-value' => $data->value,
            ]
        );
    }

    public function deleted(CustomerEquipmentData $data): void
    {
        //
    }

    public function restored(CustomerEquipmentData $data): void
    {
        //
    }

    public function forceDeleted(CustomerEquipmentData $data): void
    {
        //
    }
}
