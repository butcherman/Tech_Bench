<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerEquipmentNoteTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.index', [
            $customer->slug,
            $equipment->cust_equip_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)->get(route('customers.equipment.notes.index', [
            $customer->slug,
            $equipment->cust_equip_id,
        ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Note/Index')
                    ->has('permissions')
                    ->has('customer')
                    ->has('noteList')
                    ->has('equipment')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.create', [
            $customer->slug,
            $equipment->cust_equip_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        // Remove the 'Add Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.create', [
                $customer->slug,
                $equipment->cust_equip_id,
            ]));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.create', [
                $customer->slug,
                $equipment->cust_equip_id,
            ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Note/Create')
                    ->has('permissions')
                    ->has('customer')
                    ->has('siteList')
                    ->has('equipmentList')
                    ->has('activeEquipment')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.show', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create([
                'cust_id' => $customer->cust_id,
                'cust_equip_id' => $equipment->cust_equip_id,
            ]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.show', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Note/Show')
                    ->has('permissions')
                    ->has('customer')
                    ->has('siteList')
                    ->has('note')
                    ->has('equipment')
            );
    }

    public function test_show_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.show', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.edit', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        // Remove the 'Edit Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create([
                'cust_id' => $customer->cust_id,
                'cust_equip_id' => $equipment->cust_equip_id,
            ]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.edit', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create([
                'cust_id' => $customer->cust_id,
                'cust_equip_id' => $equipment->cust_equip_id,
            ]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.edit', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Note/Edit')
                    ->has('permissions')
                    ->has('customer')
                    ->has('siteList')
                    ->has('equipmentList')
                    ->has('note')
                    ->has('equipment')
            );
    }

    public function test_edit_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.notes.edit', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertStatus(404);
    }
}
