<?php

namespace Tests\Feature\Equipment;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Tests\TestCase;

class EquipmentCategoryTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->get(route('equipment-category.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('equipment-category.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('equipment-category.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equipment.category.created'));
        $this->assertDatabaseHas('equipment_categories', ['name' => 'Cisco']);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->put(route('equipment-category.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_user()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('equipment-category.update', $cat->cat_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('equipment-category.update', $cat->cat_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equipment.category.updated'));
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $form['name']]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->delete(route('equipment-category.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_user()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('equipment-category.destroy', $cat->cat_id));
        $response->assertStatus(403);
    }

    public function test_destroy_with_equipment()
    {
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment-category.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['query_error' => __('equipment.category.in-use', ['name' => $cat->name])]);
        $this->assertDatabaseHas('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }

    public function test_destroy()
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('equipment-category.destroy', $cat->cat_id));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('equipment.category.destroyed'));
        $this->assertDatabaseMissing('equipment_categories', ['cat_id' => $cat->cat_id, 'name' => $cat->name]);
    }
}
