<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTableValue;
use App\Models\WorkbookValue;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
    public function getAllWorkbookValues(CustomerEquipmentWorkbook $workbook, bool $incPrivate = false): array
    {

        $baseValues = $incPrivate ? $workbook->WorkbookValues : $workbook->PublicWorkbookValues;
        $baseValues = $baseValues->pluck('value', 'index')->all();

        $tableValues = $incPrivate ? $workbook->WorkbookTableValues : $workbook->PublicWorkbookTableValues;
        $tableValues = $tableValues->groupBy('table_index');

        // Group and build the individual tables
        foreach ($tableValues as $tableIndex => $table) {
            $baseValues[$tableIndex] = $this->formatTableValues($table);
        }

        return $baseValues;
    }

    /**
     * Get the values for a specific table and fill in any missing columns
     */
    public function getWorkbookTableValues(CustomerEquipmentWorkbook $workbook, string $tableIndex): array
    {
        $table = $this->findTableByIndex($workbook->wb_skeleton, $tableIndex);
        $headers = $this->getTableHeaders($workbook, $tableIndex);
        $tableValues = $workbook->WorkbookTableValues->where('table_index', $tableIndex);
        $formattedValues = $this->formatTableValues($tableValues);

        // Check formatted values for any missing columns
        foreach ($formattedValues as $index => $row) {
            // Go through each column and make sure it exists
            foreach ($table['props']['columns'] as $col) {
                if (! array_key_exists($col['name'], $row)) {
                    $row[$col['name']] = null;
                }
            }

            // Unset the Index field
            unset($row['index']);

            // Put the fields in the same order as the table
            $formattedValues[$index] = array_replace(array_flip($headers), $row);

        }

        return $formattedValues;
    }

    /**
     * Save a workbook value or workbook table value by index into the database
     */
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
    | Table methods
    |---------------------------------------------------------------------------
    */

    /**
     * Get the column names and order from the table
     */
    public function getTableHeaders(CustomerEquipmentWorkbook $workbook, string $tableIndex): array
    {
        $table = $this->findTableByIndex($workbook->wb_skeleton, $tableIndex);
        $headers = [];

        foreach ($table['props']['columns'] as $col) {
            $headers[] = $col['name'];
        }

        return $headers;
    }

    /**
     * Find a tables structure based on its index
     */
    protected function findTableByIndex(array $workbookSkeleton, string $tableIndex): ?array
    {
        if (isset($workbookSkeleton['index']) && $workbookSkeleton['index'] === $tableIndex) {
            return $workbookSkeleton;
        }

        foreach ($workbookSkeleton as $value) {
            if (is_array($value)) {
                $result = $this->findTableByIndex($value, $tableIndex);

                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    /*
    |---------------------------------------------------------------------------
    | Cleanup Functions for Workbooks
    |---------------------------------------------------------------------------
    */

    /**
     * Remove workbook builder data from customer workbook
     */
    protected function removeBuilderData(array $workbookSkeleton): array
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

    /**
     * Format table values to be displayed by the UI
     */
    protected function formatTableValues(Collection|EloquentCollection $tableRows): array
    {
        // Group and build the individual rows
        $rows = $tableRows->groupBy('row_index');
        $newTable = [];

        // Cycle through all rows and format to column_name => value
        foreach ($rows as $rowIndex => $rowValue) {
            $newRow = [];
            foreach ($rowValue as $value) {
                $newRow[$value->column_name] = $value->value;
            }

            $newRow['index'] = $rowIndex;
            $newTable[] = $newRow;
        }

        return $newTable;
    }
}
