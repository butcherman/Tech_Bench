<?php

namespace Tests\Feature\Equipment;

use App\Models\DataFieldType;
use App\Models\User;
use Tests\TestCase;

class DataTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('data_types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('data_types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data_types.index'));
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

        $response = $this->post(route('data_types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('data_types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('data_types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equip.data_type.created'));
        $this->assertDatabaseHas('data_field_types', $data);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->get(route('data_types.show', $type->type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('data_types.show', $type->type_id));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data_types.show', $type->type_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->put(route('data_types.update', $type->type_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('data_types.update', $type->type_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('data_types.update', $type->type_id), $data);
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

        $response = $this->delete(route('data_types.destroy', $type->type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('data_types.destroy', $type->type_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('data_types.destroy', $type->type_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equip.data_type.destroyed'));
        $this->assertDatabaseMissing('data_field_types', $type->only(['type_id', 'name']));
    }
}
