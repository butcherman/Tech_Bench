<?php

namespace Tests\Feature\Equipment;

use App\Exceptions\Database\RecordInUseException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EquipmentDataTypeTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('equipment-data.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('equipment-data.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('equipment-data.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/DataType/Index')
                    ->has('data-types')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('equipment-data.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('equipment-data.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('equipment-data.create'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/DataType/Create')
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
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => false,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->post(route('equipment-data.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => false,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment-data.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => false,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment-data.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                __('equipment.data-field-type.created')
            );

        $this->assertDatabaseHas('data_field_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $type = DataFieldType::factory()->create();

        $response = $this->get(route('equipment-data.edit', $type->type_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('equipment-data.edit', $type->type_id));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('equipment-data.edit', $type->type_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/DataType/Edit')
                    ->has('data-field-type')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => true,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->put(
            route('equipment-data.update', $type->type_id),
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
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => true,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('equipment-data.update', $type->type_id), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $type = DataFieldType::factory()->create();

        $data = [
            'name' => 'Something Random',
            'pattern' => null,
            'masked' => false,
            'is_hyperlink' => true,
            'allow_copy' => false,
            'do_not_log_value' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('equipment-data.update', $type->type_id), $data);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                __('equipment.data-field-type.updated')
            );

        $this->assertDatabaseHas('data_field_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $type = DataFieldType::factory()->create();

        $response = $this->delete(
            route('equipment-data.destroy', $type->type_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment-data.destroy', $type->type_id));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $type = DataFieldType::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment-data.destroy', $type->type_id));

        $response->assertStatus(302)
            ->assertSessionHas(
                'warning',
                __('equipment.data-field-type.destroyed')
            );

        $this->assertDatabaseMissing(
            'data_field_types',
            $type->only(['type_id', 'name'])
        );
    }

    public function test_destroy_in_use(): void
    {
        Exceptions::fake();

        $this->withoutExceptionHandling();
        $this->expectException(RecordInUseException::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $type = DataFieldType::factory()->create();
        $equip = EquipmentType::factory()->create();
        DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => $type->type_id,
            'order' => 0,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('equipment-data.destroy', $type->type_id));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(
            'query_error',
            __('equipment.data-field-type.in-use')
        );

        $this->assertDatabaseHas(
            'data_field_types',
            $type->only(['type_id', 'name'])
        );

        Exceptions::assertReported(RecordInUseException::class);
    }
}
