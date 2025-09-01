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
    public function test_update_workbook_builder_new_workbook(): void
    {
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $testObj = new EquipmentWorkbookService;
        $testObj->updateWorkbookBuilder(collect($data), $equip);

        $this->assertDatabaseHas('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
        ]);
    }

    public function test_update_workbook_builder_existing_workbook(): void
    {
        $equip = EquipmentType::factory()->create();
        EquipmentWorkbook::create([
            'equip_id' => $equip->equip_id,
            'workbook_data' => json_encode(['data']),
            'version_hash' => 'unit_test',
        ]);
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $testObj = new EquipmentWorkbookService;
        $testObj->updateWorkbookBuilder(collect($data), $equip);

        $this->assertDatabaseHas('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
        ]);

        $this->assertDatabaseMissing('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
            'version_hash' => 'unit_test',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | getWorkbook()
    |---------------------------------------------------------------------------
    */
    public function test_get_workbook(): void
    {
        $equip = EquipmentType::factory()->create();
        $wbData = json_encode([
            'body' => [],
            'footer' => [],
            'header' => [],
        ]);
        EquipmentWorkbook::create([
            'equip_id' => $equip->equip_id,
            'workbook_data' => $wbData,
            'version_hash' => 'unit_test',
        ]);

        $testObj = new EquipmentWorkbookService;
        $res = $testObj->getWorkbook($equip);
        $this->assertEquals(json_decode($wbData), $res);
    }

    public function test_get_workbook_missing(): void
    {
        $equip = EquipmentType::factory()->create();

        $testObj = new EquipmentWorkbookService;
        $res = $testObj->getWorkbook($equip);

        $this->assertFalse($res);
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
}
