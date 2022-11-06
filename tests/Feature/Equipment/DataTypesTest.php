<?php

namespace Tests\Feature\Equipment;

use App\Models\DataField;
use Tests\TestCase;
use App\Models\User;
use App\Models\DataFieldType;
use App\Models\EquipmentType;

class DataTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('data-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('data-types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data-types.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->post(route('data-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('data-types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('data-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equip.data_type.created'));
        $this->assertDatabaseHas('data_field_types', $data);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name'    => 'Something Random',
        ];

        $response = $this->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name'    => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name'    => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equip.data_type.updated'));
        $this->assertDatabaseHas('data_field_types', $data);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->delete(route('data-types.destroy', $type->type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('data-types.destroy', $type->type_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('data-types.destroy', $type->type_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equip.data_type.destroyed'));
        $this->assertDatabaseMissing('data_field_types', $type->only(['type_id', 'name']));
    }
}
