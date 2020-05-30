<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\GetEquipment;
use App\SystemTypes;
use Tests\TestCase;

class GetEquipmentTest extends TestCase
{
    protected $testEquip, $testObj;

    public function setUp():void
    {
        Parent::setup();

        $this->testEquip = factory(SystemTypes::class, 5)->create();
        $this->testObj = new GetEquipment;
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
}
