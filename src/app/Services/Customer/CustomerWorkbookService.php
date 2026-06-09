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

        $wbData = $this->removeBuilderData($equipWorkbook->workbook_data);

        $equip->EquipmentWorkbook()->create([
            'wb_hash' => Str::uuid(),
            'wb_skeleton' => $wbData,
            'wb_version' => $equipWorkbook->version_hash,
            'cust_id' => $equip->cust_id,
        ]);
    }

    /**
     * Replace the workbook skeleton with the newer version
     */
    public function updateWorkbook(CustomerEquipment $equip): void
    {
        $currentWorkbook = $equip->EquipmentWorkbook;
        $newWorkbook = $equip->EquipmentType->EquipmentWorkbook;

        $wbData = $this->removeBuilderData($newWorkbook->workbook_data);

        $currentWorkbook->wb_version = $newWorkbook->version_hash;
        $currentWorkbook->wb_skeleton = $wbData;
        $currentWorkbook->save();
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
    public function getAllWorkbookValues(CustomerEquipmentWorkbook $workbook, bool $incPrivate = false)
    {

        $baseValues = $incPrivate ? $workbook->WorkbookValues : $workbook->PublicWorkbookValues;
        $baseValues = $baseValues->pluck('value', 'index')->all();

        $tableValues = $incPrivate ? $workbook->WorkbookTableValues : $workbook->PublicWorkbookTableValues;
        $tableValues = $tableValues->groupBy('table_index');

        // Group and build the individual tables
        foreach ($tableValues as $tableIndex => $table) {

            // Group and build the individual rows
            $rows = $table->groupBy('row_index');
            $newTable = [];

            foreach ($rows as $rowIndex => $rowValue) {
                $newRow = [];
                foreach ($rowValue as $value) {
                    $newRow[$value->column_name] = $value->value;
                }

                $newRow['index'] = $rowIndex;
                $newTable[] = $newRow;
            }

            $baseValues[$tableIndex] = $newTable;
        }

        return $baseValues;
    }

    public function saveWorkbookValue(CustomerEquipmentWorkbook $workbook, Collection $requestData): void
    {
        if (! $requestData->get('isTable')) {
            $updatable = WorkbookValue::firstOrCreate([
                'wb_id' => $workbook->wb_id,
                'index' => $requestData->get('index'),
            ]);
        }

        if ($requestData->get('isTable')) {

            $updatable = WorkbookTableValue::firstOrCreate([
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

    /*
    |---------------------------------------------------------------------------
    | Cleanup Functions for Workbooks
    |---------------------------------------------------------------------------
    */

    /**
     * Remove workbook builder data from customer workbook
     */
    protected function removeBuilderData(array $workbookSkeleton)
    {
        $targetKey = 'nodeHelper';

        foreach ($workbookSkeleton as $key => $value) {
            if ($key === $targetKey) {
                unset($workbookSkeleton[$key]);
            } elseif (is_array($value)) {
                $workbookSkeleton[$key] = $this->removeBuilderData($value);
            }
        }

        return $workbookSkeleton;
    }
}
