<?php

namespace Tests\Feature\Admin\Misc;

use App\Exceptions\Database\RecordInUseException;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ContactPhoneTypesTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.phone-types.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.phone-types.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.phone-types.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/PhoneType/Index')
                    ->has('phone-types')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
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

    public function test_store_no_permission(): void
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

    public function test_store(): void
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

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
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

    public function test_update_no_permission(): void
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

    public function test_update(): void
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

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $phoneType = PhoneNumberType::find(1);

        $response = $this->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $phoneType = PhoneNumberType::find(1);

        $response = $this->actingAs($user)->delete(
            route('admin.phone-types.destroy', $phoneType->phone_type_id)
        );

        $response->assertForbidden();
    }

    public function test_destroy(): void
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

    public function test_destroy_in_use(): void
    {
        Exceptions::fake();

        $this->withoutExceptionHandling();
        $this->expectException(RecordInUseException::class);

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

        Exceptions::assertReported(RecordInUseException::class);
    }
}
