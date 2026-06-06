<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTableValue;
use App\Models\WorkbookValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomerWorkbookService
{
    /**
     * Create a new blank workbook for the Customer Equipment
     */
    public function createWorkbook(CustomerEquipment $equip): void
    {
        $equipWorkbook = $equip->EquipmentType->EquipmentWorkbook;

        $equip->EquipmentWorkbook()->create([
            'wb_hash' => Str::uuid(),
            'wb_skeleton' => $equipWorkbook->workbook_data,
            'wb_version' => $equipWorkbook->version_hash,
            'cust_id' => $equip->cust_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Workbook Publishing
    |---------------------------------------------------------------------------
    */

    /**
     * Set the publish_until date on the workbook to allow public access
     */
    public function publishWorkbook(CustomerEquipment $equipment, string $publish_until): void
    {
        $equipment->EquipmentWorkbook->publish_until = $publish_until.' 23:59:59';
        $equipment->EquipmentWorkbook->save();
    }

    /**
     * Set the public_until field to null, removing public access
     */
    public function unPublishWorkbook(CustomerEquipment $equipment): void
    {
        $equipment->EquipmentWorkbook->publish_until = null;
        $equipment->EquipmentWorkbook->save();
    }

    /*
    |---------------------------------------------------------------------------
    | Workbook Values
    |---------------------------------------------------------------------------
    */
    public function saveWorkbookValue(CustomerEquipmentWorkbook $workbook, Collection $requestData): void
    {
        if (! $requestData->get('isTable')) {
            $updatable = WorkbookValue::create([
                'wb_id' => $workbook->wb_id,
                'index' => $requestData->get('index'),
            ]);
        }

        if ($requestData->get('isTable')) {

            $updatable = WorkbookTableValue::create([
                'wb_id' => $workbook->wb_id,
                'table_index' => $requestData->get('table_index'),
                'row_index' => $requestData->get('row_index'),
                'column_name' => $requestData->get('column_name'),
            ]);
        }

        $updatable->value = $requestData->get('value');
        $updatable->public = $requestData->get('public');
        $updatable->save();
    }
}
