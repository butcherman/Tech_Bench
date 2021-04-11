<?php

namespace Tests\Feature\Equip;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentTypesTest extends TestCase
{
    /*
    *   Index function
    */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.equipment.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_user()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.equipment.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create function
    */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.equipment.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_user()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 4]))->get(route('admin.equipment.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.create'));
        $response->assertSuccessful();
    }

    /*
    *   Store function
    */
    public function test_store_guest()
    {
        $category = EquipmentCategory::factory()->create();
        $equip    = EquipmentType::factory()->make();
        $form  = [
            'cat_id' => $category->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(route('admin.equipment.store'), $form);

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_user()
    {
        $category = EquipmentCategory::factory()->create();
        $equip    = EquipmentType::factory()->make();
        $form  = [
            'cat_id' => $category->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.equipment.store'), $form);

        $response->assertStatus(403);
    }

    public function test_store()
    {
        $category = EquipmentCategory::factory()->create();
        $equip    = EquipmentType::factory()->make();
        $form  = [
            'cat_id' => $category->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.equipment.store'), $form);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseHas('equipment_types',  ['cat_id' => $category->cat_id, 'name' => $equip->name]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Your Mom']);
    }

    /*
    *   Edit function
    */
    public function test_edit_guest()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('admin.equipment.edit', $equip->equip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_user()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 4]))->get(route('admin.equipment.edit', $equip->equip_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.edit', $equip->equip_id));
        $response->assertSuccessful();
    }

    /*
    *   Update Function
    */
    public function test_update_guest()
    {
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip    = EquipmentType::factory()->make();
        $form  = [
            'cat_id' => $category->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(route('admin.equipment.store', $existing->equip_id), $form);

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_user()
    {
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip    = EquipmentType::factory()->make();
        $form  = [
            'cat_id' => $category->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.equipment.store', $existing->equip_id), $form);

        $response->assertStatus(403);
    }

    public function test_update()
    {
        $existing = EquipmentType::factory()->create();
        $equip    = EquipmentType::factory()->make();
        DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id'  => 1,
            'order'    => 0,
        ]);
        $form  = [
            'cat_id' => EquipmentCategory::find($existing->cat_id)->name,
            'name'   => $equip->name,
            'data_fields' => [
                'IP Address',
                'Gateway',
                'New Field',
            ],
            'del_fields' => [
                DataFieldType::find(1)->name,
            ]
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.equipment.update', $existing->equip_id), $form);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.index'));

        $this->assertDatabaseHas('equipment_types',  ['equip_id' => $existing->equip_id, 'cat_id' => $existing->cat_id, 'name' => $form['name']]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);
    }

    /*
    *   Destroy function
    */
    public function test_destroy_guest()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->delete(route('admin.equipment.destroy', $equip->equip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_user()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.equipment.destroy', $equip->equip_id));

        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.equipment.destroy', $equip->equip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseMissing('equipment_types', ['equip_id' => $equip->equip_id]);
    }
}
