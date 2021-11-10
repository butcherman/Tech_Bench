<?php

namespace Tests\Feature\Equipment;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentCategoryTest extends TestCase
{
    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('equipment-categories.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('equipment-categories.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment-categories.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $form = [
            'name' => 'New Category Name',
        ];
        $response = $this->post(route('equipment-categories.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_user()
    {
        $form = [
            'name' => 'New Category Name',
        ];
        $response = $this->actingAs(User::factory()->create())->post(route('equipment-categories.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $form = [
            'name' => 'New Category Name',
        ];
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('equipment-categories.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'New Category Created']);
        $this->assertDatabaseHas('equipment_categories', ['name' => $form['name']]);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->get(route('equipment-categories.edit', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_user()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('equipment-categories.edit', $cat->cat_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('equipment-categories.edit', $cat->cat_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->put(route('equipment-categories.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_user()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('equipment-categories.update', $cat->cat_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'New Equipment Name',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('equipment-categories.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Category Updated']);
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $form['name']]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->delete(route('equipment-categories.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_user()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('equipment-categories.destroy', $cat->cat_id));
        $response->assertStatus(403);
    }

    public function test_destroy_with_equipment()
    {
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment-categories.destroy', $cat->cat_id));
        $response->assertStatus(500);
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }

    public function test_destroy()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment-categories.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Equipment Category Deleted']);
        $this->assertDatabaseMissing('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }
}
