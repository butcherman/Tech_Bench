<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Models\EquipmentWorkbook;

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
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'wb_data' => $blankWorkbook->workbook_data,
        ]);
    }
}
