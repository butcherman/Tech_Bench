<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\GetEquipment;
use App\SystemDataFields;
use App\SystemTypes;
use Tests\TestCase;

class GetEquipmentTest extends TestCase
{
    protected $testEquip, $testObj, $testFields;

    public function setUp():void
    {
        Parent::setup();

        $this->testEquip = factory(SystemTypes::class, 5)->create();
        $this->testObj = new GetEquipment;
        $this->testFields = factory(SystemDataFields::class, 5)->create([
            'sys_id' => $this->testEquip[0]->sys_id,
        ]);
    }

    public function test_get_all_equipment()
    {
        $data = $this->testObj->getAllEquipment();

        $this->assertEquals($this->testEquip->toArray(), $data->toArray());
    }

    public function test_get_all_equipment_with_cat()
    {
        $data = $this->testObj->getAllEquipment(true);

        $this->assertCount(5, $data);
    }

    public function test_get_cat_list()
    {
        $data = $this->testObj->getCatList();
        $this->assertCount(5, $data);
    }

    public function test_get_equipment_data()
    {
        $data = $this->testObj->getEquipmentData($this->testEquip[0]->sys_id);
        // dd($this->testFields->toArray());
        $this->assertEquals(['sys_id' => $data->sys_id, 'name' => $data->name], [
            'sys_id' => $this->testEquip[0]->sys_id,
            'name'  => $this->testEquip[0]->name,
        ]);
        $this->assertCount(5, $data->SystemDataFields);
    }
}
