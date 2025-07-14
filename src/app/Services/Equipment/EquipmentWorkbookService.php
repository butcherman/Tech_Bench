<?php

namespace App\Services\Equipment;

use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
            'workbook_data' => json_encode($requestData->get('workbook_data')),
            'version_hash' => Str::random(5),
        ])->update([
            'workbook_data' => json_encode($requestData->get('workbook_data')),
            'version_hash' => Str::random(5),
        ]);
    }

    /**
     * Return the Equipment Types workbook, or default blank workbook
     */
    public function getWorkbook(EquipmentType $equipment_type, bool $getDefault = false): mixed
    {
        $workbookData = $equipment_type->EquipmentWorkbook;

        if (! $workbookData && $getDefault) {
            return $this->getDefaultWorkbook($equipment_type);
        }

        if (! $workbookData) {
            return false;
        }

        return json_decode($workbookData->workbook_data);
    }

    /**
     * Default Workbook Data
     */
    protected function getDefaultWorkbook(EquipmentType $equipment_type): array
    {
        return [
            'header' => [
                [
                    'index' => Str::uuid(),
                    'type' => 'static',
                    'tag' => 'h3',
                    'text' => '[ Customer Name ]',
                    'class' => 'text-center',
                ],
                [
                    'index' => Str::uuid(),
                    'type' => 'static',
                    'tag' => 'h3',
                    'text' => $equipment_type->name,
                    'class' => 'text-center',
                ],
            ],
            'body' => [
                [
                    'page' => Str::uuid(),
                    'title' => 'Page 1',
                    'canPublish' => true,
                    'container' => [],
                ],
            ],
            'footer' => [],
        ];
    }
}
