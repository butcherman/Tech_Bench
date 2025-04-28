<?php

namespace App\Services\Customer;

use App\Exceptions\Customer\CustomerEquipmentDataFailedVerificationException;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;

class CustomerEquipmentDataService
{
    /**
     * Create Data Fields for newly created Customer Equipment.
     */
    public function createCustomerEquipmentDataFields(CustomerEquipment $custEquip): void
    {
        $dataFields = DataField::where('equip_id', $custEquip->equip_id)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($dataFields as $field) {
            $custEquip->CustomerEquipmentData()->create([
                'field_id' => $field->field_id,
                'value' => null,
            ]);
        }
    }

    /**
     * Add any missing data fields for all customers with selected equipment
     */
    public function updateEquipmentDataFieldsForEquipment(EquipmentType $equip): void
    {
        // All Data Fields for this equipment
        $equipDataFields = DataField::where('equip_id', $equip->equip_id)
            ->get()
            ->pluck('field_id');

        // All customer equipment Models with selected equipment
        $customerList = CustomerEquipment::where('equip_id', $equip->equip_id)
            ->get();

        // Cycle through customer list and add as necessary
        foreach ($customerList as $custEquip) {
            $this->verifyCustomerEquipmentDataFields($custEquip, $equipDataFields);
        }
    }

    /**
     * Update the value for a specific data field.
     */
    public function updateDataFieldValue(Collection $requestData, CustomerEquipment $equipment): void
    {
        foreach ($requestData->get('saveData') as $data) {
            $newData = CustomerEquipmentData::find($data['fieldId']);

            // dd($newData);

            // Verify the equipment data id belongs to the selected equipment.
            if ($newData->cust_equip_id !== $equipment->cust_equip_id) {
                throw new CustomerEquipmentDataFailedVerificationException(
                    $newData->cust_equip_id,
                    $equipment->cust_equip_id
                );
            }

            $newData->value = $data['value'];
            $newData->save();
        }
    }

    /**
     * Check All Customer Equipment Data Fields
     */
    // public function checkAllCustomerEquipment(bool $fix, ?ProgressBar $progress = null): array
    // {
    //     $custEquip = CustomerEquipment::all();
    //     $missing = [];

    //     foreach ($custEquip as $equipment) {

    //         $equipDataFields = DataField::where('equip_id', $equipment->equip_id)
    //             ->get()
    //             ->pluck('field_id');

    //         $missing[] = $this->verifyDataFields(
    //             $equipment,
    //             $equipDataFields,
    //             $fix
    //         );

    //         if ($progress) {
    //             $progress->advance();
    //         }
    //     }

    //     return $missing;
    // }

    /*
    |---------------------------------------------------------------------------
    | Private Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Determine which data fields are missing
     */
    protected function verifyCustomerEquipmentDataFields(
        CustomerEquipment $custEquip,
        Collection $equipDataFields,
        ?bool $fix = true
    ): Collection {
        // Get the customers existing Data Fields
        $customerDataFields = CustomerEquipmentData::where(
            'cust_equip_id',
            $custEquip->cust_equip_id
        )
            ->get()
            ->pluck('field_id');

        // Determine which fields are missing
        $fieldsToAdd = $equipDataFields->diff($customerDataFields);

        // Add all missing fields
        if ($fix) {
            foreach ($fieldsToAdd as $fieldId) {
                $custEquip->CustomerEquipmentData()->create([
                    'field_id' => $fieldId,
                    'value' => null,
                ]);
            }
        }

        return $fieldsToAdd;
    }
}
