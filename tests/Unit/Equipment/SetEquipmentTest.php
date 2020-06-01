<?php

namespace Tests\Unit\Equipment;

use App\CustomerSystemData;
use App\CustomerSystems;
use App\Domains\Equipment\SetEquipment;
use App\Http\Requests\Equipment\EquipmentRequest;
use App\SystemDataFields;
use App\SystemDataFieldTypes;
use App\SystemTypes;
use Tests\TestCase;

class SetEquipmentTest extends TestCase
{
    public function test_create_equipment()
    {
        $makeEquip = factory(SystemTypes::class)->make();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => $makeEquip->name,
            'cat_id' => $makeEquip->cat_id,
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $res = (new SetEquipment)->createEquipment(new EquipmentRequest($data));
        $this->assertTrue($res);
        $this->assertDatabaseHas('system_types', ['name' => $data['name'], 'cat_id' => $data['cat_id']]);
        foreach($data['system_data_fields'] as $field)
        {
            $this->assertDatabaseHas('system_data_field_types', ['name' => $field, 'hidden' => 0]);
        }
    }

    public function test_update_equipment()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();
        factory(SystemDataFields::class, 3)->create(['sys_id' => $makeEquip->sys_id]);

        $data = [
            'name'   => 'New System Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = [
                'data_type_id' => $field->data_type_id,
                'field_id' => $field->field_id,
            ];
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $res = (new SetEquipment)->updateEquipment(new EquipmentRequest($data), $makeEquip->sys_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('system_types', ['name' => $data['name'], 'sys_id' => $makeEquip->sys_id]);
        foreach($custField as $field)
        {
            $this->assertDatabaseHas('system_data_field_types', ['name' => $field->name, 'hidden' => 0]);
        }
    }

    public function test_update_equipment_fail()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFields::class, 3)->create(['sys_id' => $makeEquip->sys_id]);

        $data = [
            'name'   => 'New System Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            factory(CustomerSystemData::class)->create([
                'field_id' => $field->field_id,
            ]);
        }

        $res = (new SetEquipment)->updateEquipment(new EquipmentRequest($data), $makeEquip->sys_id);

        $this->assertTrue(is_array($res));
        $this->assertDatabaseHas('system_types', ['name' => $makeEquip->name, 'sys_id' => $makeEquip->sys_id]);
    }

    public function test_delete_equipment()
    {
        $equip = factory(SystemTypes::class)->create();

        $res = (new SetEquipment)->deleteEquipment($equip->sys_id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('system_types', ['sys_id' => $equip->sys_id]);
    }

    public function test_delete_equipment_fail()
    {
        $equip = factory(SystemTypes::class)->create();
        factory(CustomerSystems::class, 5)->create(['sys_id' => $equip->sys_id]);

        $res = (new SetEquipment)->deleteEquipment($equip->sys_id);
        $this->assertFalse($res);
        $this->assertDatabaseHas('system_types', ['sys_id' => $equip->sys_id]);
    }
}
