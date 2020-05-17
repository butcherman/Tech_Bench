<?php

namespace Tests\Unit\Equipment;

use Tests\TestCase;

use App\Domains\Equipment\GetEquipmentData;

use App\SystemTypes;
use App\SystemDataFields;

class GetEquipmentDataTest extends TestCase
{
    protected $testObj, $testEquip;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testObj   = new GetEquipmentData;
        $this->testEquip = factory(SystemTypes::class, 10)->create();
        foreach($this->testEquip as $equip)
        {
            factory(SystemDataFields::class, 3)->create([
                'sys_id' => $equip->sys_id,
            ]);
        }
    }

    public function test_get_all_equipment_no_cat()
    {
        $data = $this->testObj->getAllEquipmentNoCat();

        $this->assertEquals($this->testEquip->toArray(), $data->toArray());
    }

    public function test_get_all_equipment_no_cat_collection()
    {
        $collection = [];
        foreach($this->testEquip as $equip)
        {
            $collection[] = [
                'text'  => $equip->name,
                'value' => $equip->sys_id,
            ];
        }
        $data = $this->testObj->getAllEquipmentNoCat(true);
        $this->assertEquals($data->toJson(), collect($collection)->toJson());
    }

    public function test_get_all_equipment_with_data_list()
    {
        $data = $this->testObj->getAllEquipmentWithDataList();

        // we will only count the results to prove that the data is there
        foreach($data as $cat)
        {
            foreach($cat->SystemTypes as $sys)
            {
                $this->assertTrue($sys->SystemDataFields->count() === 3);
            }
        }
    }
}
