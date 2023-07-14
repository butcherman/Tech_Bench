<?php

namespace Tests\Feature\Equipment;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Models\User;
use Tests\TestCase;

class DataTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('data-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
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
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('data-types.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('data-types.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data-types.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->post(route('data-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('data-types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('data-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equipment.data-field-type.created'));
        $this->assertDatabaseHas('data_field_types', $data);
    }

    /**
     * Show Method
     */
    // public function test_show_guest()
    // {
    //     $type = DataFieldType::factory()->create();

    //     $response = $this->get(route('data_types.show', $type->type_id));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_show_no_permission()
    // {
    //     $type = DataFieldType::factory()->create();

    //     $response = $this->actingAs(User::factory()->create())->get(route('data_types.show', $type->type_id));
    //     $response->assertStatus(403);
    // }

    // public function test_show()
    // {
    //     $type = DataFieldType::factory()->create();

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data_types.show', $type->type_id));
    //     $response->assertSuccessful();
    // }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->get(route('data-types.edit', $type->type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('data-types.edit', $type->type_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('data-types.edit', $type->type_id));
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
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'required' => 1,
            'masked' => 0,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('data-types.update', $type->type_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('equipment.data-field-type.updated'));
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
        $response->assertRedirect(route('login'));
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
        $response->assertSessionHas('warning', __('equipment.data-field-type.destroyed'));
        $this->assertDatabaseMissing('data_field_types', $type->only(['type_id', 'name']));
    }

    public function test_destroy_in_use()
    {
        $type = DataFieldType::factory()->create();
        $equip = EquipmentType::factory()->create();
        DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => $type->type_id,
            'order' => 0,
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('data-types.destroy', $type->type_id));

        // dd($response->getSession());

        $response->assertStatus(302);
        $response->assertSessionHasErrors('query_error', __('equipment.data-field-type.in-use'));
        $this->assertDatabaseHas('data_field_types', $type->only(['type_id', 'name']));
    }
}
