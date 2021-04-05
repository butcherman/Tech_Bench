<?php

namespace Tests\Feature\Equip;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentCategoriesTest extends TestCase
{
    /*
    *   Index Function
    */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.equipment.categories.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_user()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.equipment.categories.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.categories.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create function
    */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.equipment.categories.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_user()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.equipment.categories.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.categories.create'));
        $response->assertSuccessful();
    }

    /*
    *   Store function
    */
    public function test_store_guest()
    {
        $form = [
            'name' => 'New Equipment Name',
        ];
        $response = $this->post(route('admin.equipment.categories.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_user()
    {
        $form = [
            'name' => 'New Equipment Name',
        ];
        $response = $this->actingAs(User::factory()->create())->post(route('admin.equipment.categories.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $form = [
            'name' => 'New Equipment Name',
        ];
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.equipment.categories.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'New Category Created']);
        $this->assertDatabaseHas('equipment_categories', ['name' => $form['name']]);
    }

    /*
    *   Edit function
    */
    public function test_edit_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->get(route('admin.equipment.categories.edit', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_user()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.equipment.categories.edit', $cat->cat_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.categories.edit', $cat->cat_id));
        $response->assertSuccessful();
    }

    /*
    *   Update function
    */
    public function test_update_guest()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->put(route('admin.equipment.categories.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_user()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.equipment.categories.update', $cat->cat_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.equipment.categories.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Category Updated']);
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $form['name']]);
    }

    /*
    *   Destroy function
    */
    public function test_destroy_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->delete(route('admin.equipment.categories.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_user()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.equipment.categories.destroy', $cat->cat_id));
        $response->assertStatus(403);
    }

    public function test_destroy_with_equipment()
    {
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.equipment.categories.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'This category has Equipment assigned to it.  Please delete this equipment before continuing']);
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }

    public function test_destroy()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.equipment.categories.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Category Deleted']);
        $this->assertDatabaseMissing('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }
}
