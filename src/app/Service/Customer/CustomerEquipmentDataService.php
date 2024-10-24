<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataService
{
    /**
     * Add Customer Equipment Data Fields to the database to store equipment data
     */
    public function createEquipmentDataFields(CustomerEquipment $custEquip)
    {
        Log::info(
            'Creating new Data Fields for Customer Equipment',
            $custEquip->toArray()
        );

        $dataFields = DataField::where('equip_id', $custEquip->equip_id)->get();

        foreach ($dataFields as $field) {
            CustomerEquipmentData::create([
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field->field_id,
                'value' => null,
            ]);
        }
    }

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
