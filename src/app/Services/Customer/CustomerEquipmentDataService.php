<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class CustomerEquipmentDataService
{
    /**
     * Create new Data Fields for Customer Equipment
     */
    public function createEquipmentDataFields(CustomerEquipment $custEquip)
    {
        $dataFields = DataField::where('equip_id', $custEquip->equip_id)->get();

        foreach ($dataFields as $field) {
            CustomerEquipmentData::create([
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field->field_id,
                'value' => null,
            ]);
        }
    }

    /**
     * Add any missing data fields for all customers with selected equipment
     */
    public function updateEquipmentDataFields(EquipmentType $equip)
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
            $this->verifyDataFields($custEquip, $equipDataFields);
        }
    }

    /**
     * Update the value for a specific data field.
     */
    public function updateDataFieldValue(Collection $requestData): void
    {
        foreach ($requestData->get('saveData') as $data) {
            $newData = CustomerEquipmentData::find($data['fieldId']);
            $newData->value = $data['value'];
            $newData->save();
        }
    }

    /**
     * Determine which data fields are missing
     */
    protected function verifyDataFields(
        CustomerEquipment $custEquip,
        Collection $equipDataFields,
        ?bool $fix = true
    ): Collection {
        // Get the customers existing Data Fields
        $dataFields = CustomerEquipmentData::where(
            'cust_equip_id',
            $custEquip->cust_equip_id
        )
            ->get()
            ->pluck('field_id');

        // Determine which fields are missing
        $fieldsToAdd = $equipDataFields->diff($dataFields);

        // Add all missing fields
        if ($fix) {
            foreach ($fieldsToAdd as $fieldId) {
                CustomerEquipmentData::create([
                    'cust_equip_id' => $custEquip->cust_equip_id,
                    'field_id' => $fieldId,
                    'value' => null,
                ]);
            }
        }

        return $fieldsToAdd;
    }

    /**
     * Check All Customer Equipment Data Fields
     */
    public function checkAllCustomerEquipment(bool $fix, ?ProgressBar $progress = null): array
    {
        $custEquip = CustomerEquipment::all();
        $missing = [];

        foreach ($custEquip as $equipment) {

            $equipDataFields = DataField::where('equip_id', $equipment->equip_id)
                ->get()
                ->pluck('field_id');

            $missing[] = $this->verifyDataFields(
                $equipment,
                $equipDataFields,
                $fix
            );

            if ($progress) {
                $progress->advance();
            }
        }

        return $missing;
    }
}
