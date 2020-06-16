<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\CustomerSystemData;
use App\CustomerSystems;
use App\Domains\Customers\SetCustomerEquipment;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use App\SystemDataFields;
use App\SystemTypes;
use Tests\TestCase;

class SetCustomerEquipmentTest extends TestCase
{
    public function test_create_new_equipment()
    {
        $cust = factory(Customers::class)->create();
        $equip = factory(SystemTypes::class)->create();
        $field = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);

        $data = new CustomerEquipmentRequest([
            'sys_id' => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared' => true,
            'fields' => [[
                'field_id' => $field->field_id,
                'value'    => $field->value,
            ]],
        ]);

        $res = (new SetCustomerEquipment)->createNewEquipment($data, $cust->cust_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_systems', ['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        $this->assertDatabaseHas('customer_system_data', ['field_id' => $field->field_id, 'value' => $field->value]);
    }

    public function test_create_new_equipment_for_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $equip  = factory(SystemTypes::class)->create();
        $field = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);

        $data = new CustomerEquipmentRequest([
            'sys_id' => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared' => true,
            'fields' => [[
                'field_id' => $field->field_id,
                'value'    => $val = 'This is a value',
            ]],
        ]);

        $res = (new SetCustomerEquipment)->createNewEquipment($data, $cust->cust_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_systems', ['cust_id' => $parent->cust_id, 'sys_id' => $equip->sys_id]);
        $this->assertDatabaseHas('customer_system_data', ['field_id' => $field->field_id, 'value' => $val]);
    }

    public function test_update_existing_equipment()
    {
        $cust    = factory(Customers::class)->create();
        $equip   = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $data = new CustomerEquipmentRequest([
            'sys_id'  => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'fields'  => [[
                'field_id' => $field->field_id,
                'value'    => $newVal = 'This is a new Value',
            ]],
        ]);

        $res = (new SetCustomerEquipment)->updateExistingEquipment($data, $cust->cust_id, $custSys->cust_sys_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_systems', ['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id, 'cust_sys_id' => $custSys->cust_sys_id]);
        $this->assertDatabaseHas('customer_system_data', ['field_id' => $field->field_id, 'value' => $newVal]);
    }

    public function test_update_existing_equipment_for_parent()
    {
        $parent  = factory(Customers::class)->create();
        $cust    = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $equip   = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $data = new CustomerEquipmentRequest([
            'sys_id'  => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'fields'  => [[
                'field_id' => $field->field_id,
                'value'    => $newVal = 'This is a new Value',
            ]],
        ]);

        $res = (new SetCustomerEquipment)->updateExistingEquipment($data, $cust->cust_id, $custSys->cust_sys_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_systems', ['cust_id' => $parent->cust_id, 'sys_id' => $equip->sys_id, 'cust_sys_id' => $custSys->cust_sys_id]);
        $this->assertDatabaseHas('customer_system_data', ['field_id' => $field->field_id, 'value' => $newVal]);
    }

    public function test_delete_equip()
    {
        $cust   = factory(Customers::class)->create();
        $equip  = factory(SystemTypes::class)->create();
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);

        $res = (new SetCustomerEquipment)->deleteEquip($custSys->cust_sys_id);
        $this->assertTrue($res);
        $this->assertSoftDeleted('customer_systems', ['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id, 'cust_sys_id' => $custSys->cust_sys_id]);
    }
}
