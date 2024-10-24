<?php

namespace Tests\Feature\Home;

use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PhoneTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.phone-types.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.phone-types.index'));

        $response->assertForbidden();
    }

    public function test_index()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.phone-types.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/PhoneType/Index')
                ->has('phone-types')
            );
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('phone-types'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('phone-types'));

        $response->assertSuccessful()
            ->assertJson(PhoneNumberType::all()->toArray());
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.phone-types.store'), $data);

        $response->assertForbidden();
    }

    public function test_store()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs($user)
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $phoneType = PhoneNumberType::find(1);
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs($user)->put(
            route('admin.phone-types.update', $phoneType->phone_type_id),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $phoneType = PhoneNumberType::find(1);
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $response = $this->actingAs($user)->put(
            route('admin.phone-types.update', $phoneType->phone_type_id),
            $data
        );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.phone-type.updated'));

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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $phoneType = PhoneNumberType::find(1);

        $response = $this->actingAs($user)->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );

        $response->assertForbidden();
    }

    public function test_destroy()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $phoneType = PhoneNumberType::find(1);

        $response = $this->actingAs($user)->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('admin.phone-type.destroyed'));

        $this->assertDatabaseMissing('phone_number_types', $phoneType->only([
            'phone_type_id',
        ]));
    }

    public function test_destroy_in_use()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $phoneType = PhoneNumberType::find(1);
        CustomerContactPhone::factory()->create([
            'phone_type_id' => $phoneType->phone_type_id,
        ]);

        $response = $this->actingAs($user)->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );

        $response->assertStatus(302)
            ->assertSessionHasErrors('query_error');

        $this->assertDatabaseHas('phone_number_types', $phoneType->only([
            'phone_type_id',
        ]));
    }
}
