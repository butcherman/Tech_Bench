<?php

namespace Tests\Unit\Services\Equipment;

use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Services\Equipment\EquipmentWorkbookService;
use Tests\TestCase;

class EquipmentWorkbookServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | updateWorkbookBuilder()
    |---------------------------------------------------------------------------
    */
    public function test_update_workbook_builder(): void
    {
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $testObj = new EquipmentWorkbookService;
        $testObj->updateWorkbookBuilder(collect($data), $equip);

        $this->assertDatabaseHas('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
        ]);

        $jsonObj = EquipmentWorkbook::find($equip->equip_id);
        $this->assertEquals($data['workbook_data'], $jsonObj->workbook_data);
    }

    public function test_update_workbook_builder_existing_workbook(): void
    {
        $equip = EquipmentType::factory()->create();
        EquipmentWorkbook::factory()->create(['equip_id' => $equip->equip_id]);

        $data = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $testObj = new EquipmentWorkbookService;
        $testObj->updateWorkbookBuilder(collect($data), $equip);

        $this->assertDatabaseHas('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
        ]);

        $jsonObj = EquipmentWorkbook::find($equip->equip_id);
        $this->assertEquals($data['workbook_data'], $jsonObj->workbook_data);
    }

    /*
    |---------------------------------------------------------------------------
    | getWorkbook()
    |---------------------------------------------------------------------------
    */
    public function test_get_workbook(): void
    {
        $equip = EquipmentType::factory()->create();
        $workbook = EquipmentWorkbook::factory()->create([
            'equip_id' => $equip->equip_id,
        ]);

        $equip->load('EquipmentWorkbook');

        $testObj = new EquipmentWorkbookService;
        $res = $testObj->getWorkbook($equip);

        $this->assertEquals($workbook->workbook_data, $res);
    }

    public function test_get_workbook_default_workbook(): void
    {
        $equip = EquipmentType::factory()->create();

        $testObj = new EquipmentWorkbookService;
        $res = $testObj->getWorkbook($equip, true);

        $this->assertArrayHasKey('header', $res);
        $this->assertArrayHasKey('body', $res);
        $this->assertArrayHasKey('footer', $res);
    }

    public function test_get_workbook_default_workbook_not_allowed(): void
    {
        $equip = EquipmentType::factory()->create();

        $testObj = new EquipmentWorkbookService;
        $res = $testObj->getWorkbook($equip);

        $this->assertFalse($res);
    }
}
