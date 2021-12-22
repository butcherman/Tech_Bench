<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerEquipmentTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $equipment = EquipmentType::factory()->create();
        $cust      = Customer::factory()->create();

        for($i=0; $i<5; $i++)
        {
            DataField::create([
                'equip_id' => $equipment->equip_id,
                'type_id'  => $i + 1,
                'order'    => $i,
            ]);
        }

        $data = [
            'cust_id'  => $cust->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => false,
            'data'     => [
                ['type_id' => 1, 'value' => 'something'],
                ['type_id' => 2, 'value' => 'something 2'],
                ['type_id' => 3, 'value' => 'something 3'],
                ['type_id' => 4, 'value' => 'something 4'],
            ],
        ];

        $response = $this->post(route('customers.equipment.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $equipment = EquipmentType::factory()->create();
        $cust      = Customer::factory()->create();

        for($i=0; $i<5; $i++)
        {
            DataField::create([
                'equip_id' => $equipment->equip_id,
                'type_id'  => $i + 1,
                'order'    => $i,
            ]);
        }

        $data = [
            'cust_id'  => $cust->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => false,
            'data'     => [
                ['type_id' => 1, 'value' => 'something'],
                ['type_id' => 2, 'value' => 'something 2'],
                ['type_id' => 3, 'value' => 'something 3'],
                ['type_id' => 4, 'value' => 'something 4'],
            ],
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 11)->update(['allow' => false]);

        $result = $this->actingAs(User::factory()->create())->post(route('customers.equipment.store'), $data);
        $result->assertStatus(403);
    }

    public function test_store()
    {
        $equipment   = EquipmentType::factory()->create();
        $equipFields = [];
        $cust        = Customer::factory()->create();

        for($i=0; $i<5; $i++)
        {
            $equipFields[$i] = DataField::create([
                'equip_id' => $equipment->equip_id,
                'type_id'  => $i + 1,
                'order'    => $i,
            ]);
        }

        $data = [
            'cust_id'  => $cust->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => false,
            'data'     => [
                ['type_id' => 1, 'value' => 'something'],
                ['type_id' => 2, 'value' => 'something 2'],
                ['type_id' => 3, 'value' => 'something 3'],
                ['type_id' => 4, 'value' => 'something 4'],
            ],
        ];

        $result = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.equipment.store'), $data);
        $result->assertStatus(302);
        $this->assertDatabaseHas('customer_equipment', ['cust_id' => $cust->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[0]->field_id, 'value' => 'something']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[1]->field_id, 'value' => 'something 2']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[2]->field_id, 'value' => 'something 3']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[3]->field_id, 'value' => 'something 4']);
    }

    public function test_store_to_parent()
    {
        $equipment   = EquipmentType::factory()->create();
        $equipFields = [];
        $cust        = Customer::factory()->create([
            'parent_id' => Customer::factory()->create(),
        ]);

        for($i=0; $i<5; $i++)
        {
            $equipFields[$i] = DataField::create([
                'equip_id' => $equipment->equip_id,
                'type_id'  => $i + 1,
                'order'    => $i,
            ]);
        }

        $data = [
            'cust_id'  => $cust->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => true,
            'data'     => [
                ['type_id' => 1, 'value' => 'something'],
                ['type_id' => 2, 'value' => 'something 2'],
                ['type_id' => 3, 'value' => 'something 3'],
                ['type_id' => 4, 'value' => 'something 4'],
            ],
        ];

        $result = $this->actingAs(User::factory()->create())->post(route('customers.equipment.store'), $data);
        $result->assertStatus(302);
        $this->assertDatabaseHas('customer_equipment', ['cust_id' => $cust->parent_id, 'equip_id' => $equipment->equip_id, 'shared' => true]);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[0]->field_id, 'value' => 'something']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[1]->field_id, 'value' => 'something 2']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[2]->field_id, 'value' => 'something 3']);
        $this->assertDatabaseHas('customer_equipment_data', ['field_id' => $equipFields[3]->field_id, 'value' => 'something 4']);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $customer  = Customer::factory()->create();
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);
        $dataField = CustomerEquipmentData::create(['cust_equip_id' => $custEquip->cust_equip_id, 'field_id' => $field->field_id, 'value' => 'something']);

        $data = [
            'cust_id'  => $customer->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => false,
            'data'     => [
                ['id' => $dataField->id, 'value' => 'New Value', 'field_name' => $field->name],
            ]
        ];

        $response = $this->put(route('customers.equipment.update', $custEquip->equip_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $customer  = Customer::factory()->create();
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);
        $dataField = CustomerEquipmentData::create(['cust_equip_id' => $custEquip->cust_equip_id, 'field_id' => $field->field_id, 'value' => 'something']);

        $data = [
            'cust_id'  => $customer->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => false,
            'data'     => [
                ['id' => $dataField->id, 'value' => 'New Value', 'field_name' => 'IP Address'],
            ]
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.equipment.update', $custEquip->cust_equip_id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_equipment_data', ['id' => $dataField->id, 'field_id' => $dataField->field_id, 'value' => 'New Value']);
    }

    public function test_update_to_parent()
    {
        $customer  = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);
        $dataField = CustomerEquipmentData::create(['cust_equip_id' => $custEquip->cust_equip_id, 'field_id' => $field->field_id, 'value' => 'something']);

        $data = [
            'cust_id'  => $customer->cust_id,
            'equip_id' => $equipment->equip_id,
            'shared'   => true,
            'data'     => [
                ['id' => $dataField->id, 'value' => 'New Value', 'field_name' => 'IP Address'],
            ]
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.equipment.update', $custEquip->cust_equip_id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_equipment', ['cust_id' => $customer->parent_id, 'equip_id' => $equipment->equip_id, 'shared' => true]);
        $this->assertDatabaseHas('customer_equipment_data', ['id' => $dataField->id, 'field_id' => $dataField->field_id, 'value' => 'New Value']);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $customer  = Customer::factory()->create();
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);
        $dataField = CustomerEquipmentData::create(['cust_equip_id' => $custEquip->cust_equip_id, 'field_id' => $field->field_id, 'value' => 'something']);

        $response = $this->delete(route('customers.equipment.destroy', $custEquip->cust_equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $customer  = Customer::factory()->create();
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.equipment.destroy', $custEquip->cust_equip_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $customer  = Customer::factory()->create();
        $equipment = EquipmentType::factory()->create();
        $custEquip = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equipment->equip_id, 'shared' => false]);
        $field     = DataField::create(['equip_id' => $equipment->equip_id, 'type_id' => 1, 'order' => 0]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.equipment.destroy', $custEquip->cust_equip_id));
        $response->assertStatus(302);
        $this->assertSoftDeleted('customer_equipment', $custEquip->only(['cust_id', 'equip_id', 'shared']));
    }

    /*
    *   Restore Function
    */
    public function test_restore_guest()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);
        $equipment->delete();
        $equipment->save();

        $response = $this->get(route('customers.equipment.restore', $equipment->cust_equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_restore_no_permission()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);
        $equipment->delete();
        $equipment->save();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.equipment.restore', $equipment->cust_equip_id));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);
        $equipment->delete();
        $equipment->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.equipment.restore', $equipment->cust_equip_id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_equipment', $equipment->only(['cust_equip_id']));
    }

    /*
    *   Force Delete Method
    */
    public function test_force_delete_guest()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);

        $response = $this->delete(route('customers.equipment.force-delete', $equipment->cust_equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_force_delete_no_permission()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.equipment.force-delete', $equipment->cust_equip_id));
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $customer  = Customer::factory()->create();
        $equip     = EquipmentType::factory()->create();
        $equipment = CustomerEquipment::create(['cust_id' => $customer->cust_id, 'equip_id' => $equip->equip_id, 'shared' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.equipment.force-delete', $equipment->cust_equip_id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('customer_equipment', $equipment->only(['cust_equip_id']));
    }
}
