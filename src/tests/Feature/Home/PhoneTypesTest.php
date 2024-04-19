<?php

namespace Tests\Feature\Home;

use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhoneTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.phone-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.phone-types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.phone-types.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('phone-types'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('phone-types'));
        $response->assertSuccessful();
        // $response->assertJson(PhoneNumberType::get()->toJson()); FIXME - check for proper json resposne
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->post(route('admin.phone-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('admin.phone-types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('admin.phone-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.phone-type.created'));

        $this->assertDatabaseHas('phone_number_types', [
            'description' => $data['description'],
            'icon_class' => $data['icon_class'],
        ]);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $phoneType = PhoneNumberType::find(1);

        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->put(
            route('admin.phone-types.update', $phoneType->phone_type_id),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $phoneType = PhoneNumberType::find(1);

        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('admin.phone-types.update', $phoneType->phone_type_id),
                $data
            );
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $phoneType = PhoneNumberType::find(1);

        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(
                route('admin.phone-types.update', $phoneType->phone_type_id),
                $data
            );
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.phone-type.updated'));

        $this->assertDatabaseHas('phone_number_types', [
            'phone_type_id' => $phoneType->phone_type_id,
            'description' => $data['description'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $phoneType = PhoneNumberType::find(1);

        $response = $this->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $phoneType = PhoneNumberType::find(1);

        $response = $this->actingAs(User::factory()->create())
            ->delete(
                route('admin.phone-types.destroy', $phoneType->phone_type_id)
            );
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $phoneType = PhoneNumberType::find(1);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(
                route('admin.phone-types.destroy', $phoneType->phone_type_id)
            );
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('admin.phone-type.destroyed'));

        $this->assertDatabaseMissing('phone_number_types', $phoneType->only([
            'phone_type_id'
        ]));
    }

    public function test_destroy_in_use()
    {
        $phoneType = PhoneNumberType::find(1);
        CustomerContactPhone::factory()->create([
            'phone_type_id' => $phoneType->phone_type_id
        ]);
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(
                route('admin.phone-types.destroy', $phoneType->phone_type_id)
            );
        $response->assertStatus(302);
        $response->assertSessionHasErrors('query_error');

        $this->assertDatabaseHas('phone_number_types', $phoneType->only([
            'phone_type_id'
        ]));
    }
}
