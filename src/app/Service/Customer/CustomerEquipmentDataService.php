<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
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

    public function updateEquipmentDataFields(EquipmentType $equip)
    {
        Log::info('Updating Customer Data Fields for Customers with '.$equip->name);

        $equipDataFields = DataField::where('equip_id', $equip->equip_id)
            ->get()
            ->pluck('field_id');

        $customerList = CustomerEquipment::where('equip_id', $equip->equip_id)
            ->get();

        // Cycle through customer list and see if any are missing, or need to add a field
        foreach ($customerList as $customer) {
            Log::debug(
                'Checking customer equipment for Customer Equipment ID '.$customer->cust_equip_id
            );

            // Get the customers existing Data Fields
            $dataFields = CustomerEquipmentData::where('cust_equip_id', $customer->cust_equip_id)
                ->get()
                ->pluck('field_id');

            $fieldsToAdd = $equipDataFields->diff($dataFields);

            foreach ($fieldsToAdd as $fieldId) {
                CustomerEquipmentData::create([
                    'cust_equip_id' => $customer->cust_equip_id,
                    'field_id' => $fieldId,
                    'value' => null,
                ]);
            }

        }
    }

    public function updateDataFieldValue(CustomerEquipmentDataRequest $requestData): void
    {
        foreach ($requestData->saveData as $data) {
            $newData = CustomerEquipmentData::find($data['fieldId']);
            $newData->value = $data['value'];
            $newData->save();
        }

        // TODO - Broadcast Data Change
    }
}
