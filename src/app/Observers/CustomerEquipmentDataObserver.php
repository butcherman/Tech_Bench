<?php

namespace App\Observers;

use App\Models\CustomerEquipmentData;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataObserver extends Observer
{
    public function created(CustomerEquipmentData $data): void
    {
        Log::debug(
            'Equipment Data Fields Created for New Customer Equipment',
            $data->toArray()
        );
    }

    public function updated(CustomerEquipmentData $data): void
    {
        $originalData = $data->getOriginal();
        $logValue = ! $data->DataFieldType->do_not_log_value;
        $redacted = '<REDACTED>';

        Log::info(
            'Customer Equipment Data Updated by '.$this->user,
            [
                'customer_equipment_id' => $data->cust_equip_id,
                'data-field' => $data->field_name,
                'old-value' => $logValue ? $originalData['value'] : $redacted,
                'new-value' => $logValue ? $data->value : $redacted,
            ]
        );
    }
}
