<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\CustomerEquipmentData;

class CustomerEquipmentDataService
{
    public function updateEquipmentDataFields(CustomerEquipmentDataRequest $requestData): void
    {
        foreach ($requestData->saveData as $data) {
            $newData = CustomerEquipmentData::find($data['fieldId']);
            $newData->value = $data['value'];
            $newData->save();
        }

        // TODO - Broadcast Data Change
    }
}
