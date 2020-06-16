<?php

namespace Tests\Feature\Customers;

use App\Customers;
use App\CustomerSystemData;
use App\CustomerSystems;
use App\SystemDataFields;
use App\SystemTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerEquipmentControllerTest extends TestCase
{
    public function test_index_guest()
    {
        factory(SystemTypes::class, 2)->create();
        $response = $this->get(route('customer.equipment.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        factory(SystemTypes::class, 2)->create();
        $response = $this->actingAs($this->getTech())->get(route('customer.equipment.index'));
        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'system_types']]);
    }

    public function test_store_guest()
    {
        $cust =  factory(Customers::class)->create();
        $equip = factory(SystemTypes::class)->create();
        $field = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);

        $data = [
            'sys_id' => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared' => true,
            'fields' => [[
                'field_id' => $field->field_id,
                'value'    => $field->value,
            ]],
        ];

        $response = $this->post(route('customer.equipment.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store()
    {
        $cust =  factory(Customers::class)->create();
        $equip = factory(SystemTypes::class)->create();
        $field = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);

        $data = [
            'sys_id' => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared' => true,
            'fields' => [[
                'field_id' => $field->field_id,
                'value'    => $field->value,
            ]],
        ];

        $response = $this->actingAs($this->getTech())->post(route('customer.equipment.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_show_guest()
    {
        $cust = factory(Customers::class)->create();
        $equip = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $response = $this->get(route('customer.equipment.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust = factory(Customers::class)->create();
        $equip = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $response = $this->actingAs($this->getTech())->get(route('customer.equipment.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJsonStructure([['cust_sys_id', 'sys_id', 'shared', 'sys_name', 'customer_system_data']]);
    }

    public function test_update_guest()
    {
        $cust    = factory(Customers::class)->create();
        $equip   = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $data = [
            'sys_id'  => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'fields'  => [[
                'field_id' => $field->field_id,
                'value'    => $newVal = 'This is a new Value',
            ]],
        ];

        $response = $this->put(route('customer.equipment.update', $custSys->cust_sys_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $cust    = factory(Customers::class)->create();
        $equip   = factory(SystemTypes::class)->create();
        $field   = factory(SystemDataFields::class)->create(['sys_id' => $equip->sys_id]);
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);
        factory(CustomerSystemData::class)->create(['cust_sys_id' => $custSys->cust_sys_id, 'field_id' => $field->field_id, 'value' => 'This is a value']);

        $data = [
            'sys_id'  => $equip->sys_id,
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'fields'  => [[
                'field_id' => $field->field_id,
                'value'    => $newVal = 'This is a new Value',
            ]],
        ];

        $response = $this->actingAs($this->getTech())->put(route('customer.equipment.update', $custSys->cust_sys_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $cust   = factory(Customers::class)->create();
        $equip  = factory(SystemTypes::class)->create();
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);

        $response = $this->delete(route('customer.equipment.destroy', $custSys->cust_sys_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $cust   = factory(Customers::class)->create();
        $equip  = factory(SystemTypes::class)->create();
        $custSys = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id, 'sys_id' => $equip->sys_id]);

        $response = $this->actingAs($this->getTech())->delete(route('customer.equipment.destroy', $custSys->cust_sys_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
