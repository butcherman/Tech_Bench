<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Models\EquipmentWorkbook;
use Illuminate\Support\Str;

class CustomerWorkbookService
{
    /**
     * Create new blank workbook for Customer Equipment
     */
    public function createWorkbook(CustomerEquipment $equipment): void
    {
        $blankWorkbook = EquipmentWorkbook::where('equip_id', $equipment->equip_id)->first();

        $wb = new CustomerWorkbook([
            'wb_hash' => Str::uuid(),
            'wb_skeleton' => $blankWorkbook->workbook_data,
            'wb_version' => $blankWorkbook->version_hash,
        ]);

        $equipment->EquipmentWorkbook()->save($wb);
    }
}
