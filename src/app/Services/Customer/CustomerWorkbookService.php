<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Models\CustomerWorkbookValue;
use App\Models\EquipmentWorkbook;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomerWorkbookService
{
    /**
     * Create a new workbook for customer equipment
     */
    public function createWorkbook(Customer $customer, CustomerEquipment $equipment): void
    {
        $blankWorkbook = EquipmentWorkbook::where('equip_id', $equipment->equip_id)
            ->first();

        CustomerWorkbook::create([
            'wb_hash' => Str::uuid(),
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'wb_data' => $blankWorkbook->workbook_data,
        ]);
    }

    /**
     * Get a Workbook for the Customer Equipment
     */
    public function getWorkbook(Customer $customer, CustomerEquipment $equipment): CustomerWorkbook|null
    {
        $workbook = CustomerWorkbook::where('cust_equip_id', $equipment->cust_equip_id)->first();

        // Update the workbook with any editing changes that need to be made
        $workbook->wb_data = str_replace('[ Customer Name ]', $customer->name, $workbook->wb_data);

        return $workbook;
    }

    /**
     * Get all value data for the selected workbook
     */
    public function getWorkbookValues(CustomerWorkbook $workbook)
    {
        return $workbook->WorkbookValues->mapWithKeys(function ($item) {
            return [$item->index => $item->value];
        });
    }

    /**
     * Set a workbook field value
     */
    public function setWorkbookValue(CustomerWorkbook $workbook, Collection $requestData)
    {
        CustomerWorkbookValue::updateOrCreate(
            [
                'wb_id' => $workbook->wb_id,
                'index' => $requestData->get('index'),
            ],
            [
                'value' => $requestData->get('fieldValue'),
            ]
        );
    }
}
