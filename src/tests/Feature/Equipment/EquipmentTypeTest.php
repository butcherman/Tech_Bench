<?php

namespace Tests\Feature\Equipment;

use App\Exceptions\Database\RecordInUseException;
use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EquipmentTypeTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('equipment.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_user(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('equipment.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('equipment.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/Index')
                    ->has('equipment-list')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('equipment.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('equipment.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('equipment.create'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/Create')
                    ->has('category-list')
                    ->has('data-list')
                    ->has('public-tips')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(route('equipment.store'), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment.store'), $form);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment.store'), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success', __('equipment.created'));

        $this->assertDatabaseHas('equipment_types', [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
        ]);

        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Your Mom']);

        // Verify order is correct
        $fields = DataFieldType::whereIn('name', $form['custData'])->get();
        $equip = EquipmentType::where('name', $equip->name)->first();

        $index = 0;
        foreach ($fields as $field) {
            $fieldData = DataField::where('equip_id', $equip->equip_id)
                ->where('type_id', $field->type_id)->first();

            $this->assertTrue($fieldData->order === $index);
            $index++;
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('equipment.edit', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 4]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('equipment.edit', $equip->equip_id));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('equipment.edit', $equip->equip_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Equipment/Edit')
                    ->has('equipment')
                    ->has('category-list')
                    ->has('data-list')
                    ->has('public-tips')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->post(
            route('equipment.store', $existing->equip_id),
            $form
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $existing = EquipmentType::factory()->create();
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $form = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('equipment.store', $existing->equip_id), $form);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $existing = EquipmentType::factory()->create();
        $equip = EquipmentType::factory()->make();

        DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);

        $form = [
            'cat_id' => $existing->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'New Field',
                'Gateway',
            ],
        ];

        CustomerEquipment::factory()
            ->count(3)
            ->create(['equip_id' => $existing->equip_id]);

        $response = $this->actingAs($user)
            ->put(route('equipment.update', $existing->equip_id), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success', __('equipment.updated'));

        $this->assertDatabaseHas('equipment_types', [
            'equip_id' => $existing->equip_id,
            'cat_id' => $existing->cat_id,
            'name' => $form['name'],
        ]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseHas('data_field_types', ['name' => 'Gateway']);

        // Verify order is correct
        $fields = DataFieldType::whereIn('name', $form['custData'])->get();
        $equip = EquipmentType::where('name', $equip->name)->first();

        $index = 0;
        foreach ($fields as $field) {
            $fieldData = DataField::where('equip_id', $equip->equip_id)
                ->where('type_id', $field->type_id)->first();

            $this->assertTrue($fieldData->order === $index);
            $index++;
        }

        Bus::assertDispatched(UpdateCustomerDataFieldsJob::class);
    }

    public function test_update_remove_field(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $existing = EquipmentType::factory()->create();
        $equip = EquipmentType::factory()->make();
        $dataField = DataFieldType::factory()->create();

        DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id' => $dataField->type_id,
            'order' => 0,
        ]);

        $form = [
            'cat_id' => $existing->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'New Field',
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('equipment.update', $existing->equip_id), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success', __('equipment.updated'));

        $this->assertDatabaseHas('equipment_types', [
            'equip_id' => $existing->equip_id,
            'cat_id' => $existing->cat_id,
            'name' => $form['name'],
        ]);
        $this->assertDatabaseHas('data_field_types', ['name' => 'IP Address']);
        $this->assertDatabaseMissing('data_fields', [
            'equip_id' => $equip->equip_id,
            'type_id' => $dataField->type_id,
        ]);

        Bus::assertDispatched(UpdateCustomerDataFieldsJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $equip = EquipmentType::factory()->create();

        $response = $this->delete(route('equipment.destroy', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_user(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment.destroy', $equip->equip_id));

        $response->assertForbidden();
    }

    public function test_destroy_in_use(): void
    {
        Exceptions::fake();

        $this->withoutExceptionHandling();
        $this->expectException(RecordInUseException::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        $cust = Customer::factory()->create();
        CustomerEquipment::create([
            'cust_id' => $cust->cust_id,
            'equip_id' => $equip->equip_id,
            'shared' => false,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('equipment.destroy', $equip->equip_id));

        $response->assertStatus(302)
            ->assertSessionHasErrors(
                'query_error',
                __('equipment.in_use', ['name' => $equip->name])
            );

        Exceptions::assertReported(RecordInUseException::class);
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('equipment.destroy', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('equipment.index'))
            ->assertSessionHas('warning', __('equipment.destroyed'));

        $this->assertDatabaseMissing('equipment_types', [
            'equip_id' => $equip->equip_id,
        ]);
    }
}
