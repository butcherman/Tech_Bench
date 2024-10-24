<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerNoteEquipmentTest extends TestCase
{
    /**
     * Create Method
     */
    public function test_create_guest()
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

    public function test_create_no_permission()
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

    public function test_create()
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
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Create')
                ->has('permissions')
                ->has('customer')
                ->has('equipment')
                ->has('siteList')
                ->has('equipmentList')
            );
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->post(
            route(
                'customers.equipment.notes.store',
                [$customer->slug, $equipment->cust_equip_id]
            ),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        // Remove the 'Add Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.equipment.notes.store', [
                $customer->slug,
                $equipment->cust_equip_id,
            ]), $data);
        $response->assertForbidden();
    }

    public function test_store()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.equipment.notes.store', [
                $customer->slug,
                $equipment->cust_equip_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);

        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_store_equipment_note()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => $equipment->cust_equip_id,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.equipment.notes.store', [
                $customer->slug,
                $equipment->cust_equip_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);

        $this->assertDatabaseHas('customer_notes', $data);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
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

    public function test_show()
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

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Show')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('note')
                ->has('equipment')
            );
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
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

    public function test_edit_no_permission()
    {
        // Remove the 'Edit Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer Note', false);

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

        $response->assertForbidden();
    }

    public function test_edit()
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

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Edit')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('equipmentList')
                ->has('note')
                ->has('equipment')
            );
    }

    /*
     *   Update Method
     */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->put(route('customers.equipment.notes.update', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        // Remove the 'Edit Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.notes.update', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.notes.update', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);

        $this->assertDatabaseHas('customer_notes', $data);
    }

    /*
     *   Destroy Function
     */
    public function test_destroy_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->delete(route('customers.equipment.notes.destroy', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $this->assertDatabaseHas('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));
    }

    public function test_destroy_no_permission()
    {
        // Remove the 'Delete Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Delete Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.notes.destroy', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertForbidden();

    }

    public function test_destroy()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.notes.destroy', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.note.deleted'));

        $this->assertSoftDeleted('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));
    }
}
