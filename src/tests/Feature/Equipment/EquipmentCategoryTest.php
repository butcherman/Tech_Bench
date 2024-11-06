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
    public function test_store_guest(): void
    {
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->post(route('equipment-category.store'), $data);
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment-category.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment-category.store'), $data);
        $response->assertStatus(302)
            ->assertSessionHas('success', __('equipment.category.created'));

        $this->assertDatabaseHas('equipment_categories', ['name' => 'Cisco']);
    }

    /**
     * Update Method
     */
    public function test_update_guest(): void
    {
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->put(
            route('equipment-category.update', $cat->cat_id),
            $form
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_user(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs($user)
            ->put(route('equipment-category.update', $cat->cat_id), $form);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cat = EquipmentCategory::factory()->create();
        $form = [
            'name' => 'Cisco',
        ];

        $response = $this->actingAs($user)
            ->put(route('equipment-category.update', $cat->cat_id), $form);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('equipment.category.updated'));

        $this->assertDatabaseHas('equipment_categories', [
            'cat_id' => $cat->cat_id,
            'name' => $form['name'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest(): void
    {
        $cat = EquipmentCategory::factory()->create();

        $response = $this->delete(
            route('equipment-category.destroy', $cat->cat_id)
        );
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_user(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment-category.destroy', $cat->cat_id));

        $response->assertForbidden();
    }

    public function test_destroy_with_equipment(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $response = $this->actingAs($user)
            ->delete(route('equipment-category.destroy', $cat->cat_id));

        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'query_error' => __('equipment.category.in-use', [
                    'name' => $cat->name,
                ]),
            ]);
        $this->assertDatabaseHas('equipment_categories', [
            'cat_id' => $cat->cat_id,
            'name' => $cat->name,
        ]);
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cat = EquipmentCategory::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment-category.destroy', $cat->cat_id));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('equipment.category.destroyed'));

        $this->assertDatabaseMissing('equipment_categories', [
            'cat_id' => $cat->cat_id,
            'name' => $cat->name,
        ]);
    }
}
