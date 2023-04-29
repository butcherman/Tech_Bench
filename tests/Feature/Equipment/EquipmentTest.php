<?php

namespace Tests\Feature\Equipment;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('equipment.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_user()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('equipment.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment.index'));
        $response->assertSuccessful();
    }

    /**
     * Create method
     */
    public function test_create_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->get(route('equipment.create', $cat->name));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('equipment.create', $cat->name));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment.create', $cat->name));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'category' => $category->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(route('equipment.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_user()
    {
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'category' => $category->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('equipment.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'category' => $category->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('equipment.store'), $form);

        $response->assertStatus(302);
        $response->assertRedirect(route('equipment.index'));
        $response->assertSessionHas('success', __('equip.created'));
        $this->assertDatabaseHas('equipment_types', ['cat_id' => $category->cat_id, 'name' => $equip->name]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Your Mom']);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('equipment.show', $equip->equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_user()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('equipment.show', $equip->equip_id));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment.show', $equip->equip_id));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('equipment.edit', $equip->equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_user()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 4]))->get(route('equipment.edit', $equip->equip_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment.edit', $equip->equip_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'category' => $category->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(route('equipment.store', $existing->equip_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_user()
    {
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'category' => $category->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('equipment.store', $existing->equip_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $existing = EquipmentType::factory()->create();
        $equip = EquipmentType::factory()->make();
        DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);
        $form = [
            'category' => EquipmentCategory::find($existing->cat_id)->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'New Field',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('equipment.update', $existing->equip_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('equipment.index'));
        $response->assertSessionHas('success', __('equip.updated'));
        $this->assertDatabaseHas('equipment_types', ['equip_id' => $existing->equip_id, 'cat_id' => $existing->cat_id, 'name' => $form['name']]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);
    }

    public function test_update_remove_field()
    {
        $existing = EquipmentType::factory()->create();
        $equip = EquipmentType::factory()->make();
        $dataField = DataFieldType::factory()->create();

        $equipField = DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id' => $dataField->type_id,
            'order' => 0,
        ]);

        //  Assign this equipment to a customer to see if the customers fields are updated as well
        $customer = Customer::factory()->create();
        $custEquip = CustomerEquipment::create([
            'cust_id' => $customer->cust_id,
            'equip_id' => $existing->equip_id,
            'shared' => false,
        ]);

        $form = [
            'category' => EquipmentCategory::find($existing->cat_id)->name,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'New Field',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('equipment.update', $existing->equip_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('equipment.index'));
        $response->assertSessionHas('success', __('equip.updated'));
        $this->assertDatabaseHas('equipment_types', ['equip_id' => $existing->equip_id, 'cat_id' => $existing->cat_id, 'name' => $form['name']]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseMissing('data_fields', ['equip_id' => $equip->equip_id, 'type_id' => $dataField->type_id]);
        $this->assertDatabaseMissing('customer_equipment_data', ['cust_equip_id' => $custEquip->cust_equip_id, 'field_id' => $equipField->field_id]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->delete(route('equipment.destroy', $equip->equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_user()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('equipment.destroy', $equip->equip_id));
        $response->assertStatus(403);
    }

    public function test_destroy_in_use()
    {
        $equip = EquipmentType::factory()->create();
        $cust = Customer::factory()->create();
        CustomerEquipment::create([
            'cust_id' => $cust->cust_id,
            'equip_id' => $equip->equip_id,
            'shared' => false,
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment.destroy', $equip->equip_id));
        $response->assertStatus(302);
        $response->assertSessionHasErrors('error', __('equip.in_use'));
    }

    public function test_destroy()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment.destroy', $equip->equip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('equipment.index'));
        $response->assertSessionHas('success', __('equip.destroyed'));
        $this->assertDatabaseMissing('equipment_types', ['equip_id' => $equip->equip_id]);
    }
}
