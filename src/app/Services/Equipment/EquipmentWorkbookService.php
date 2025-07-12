<?php

namespace App\Services\Equipment;

use App\Models\EquipmentWorkbook;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;

class EquipmentWorkbookService
{
    /**
     * Create or Update a workbook for equipment
     */
    public function updateWorkbookBuilder(Collection $requestData, EquipmentType $equipment_type): void
    {
        EquipmentWorkbook::firstOrCreate([
            'equip_id' => $equipment_type->equip_id,
        ], [
            'workbook_data' => json_encode($requestData->get('workbook_data'))
        ])->update([
                    'workbook_data' => json_encode($requestData->get('workbook_data')),
                ]);
    }
}
