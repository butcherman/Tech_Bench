<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerNoteEquipmentTest extends TestCase
{
    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.create', [$customer->slug, $equipment->cust_equip_id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 17)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.notes.create', [$customer->slug, $equipment->cust_equip_id]));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.notes.create', [$customer->slug, $equipment->cust_equip_id]));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
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

        $response = $this->post(
            route(
                'customers.equipment.notes.store',
                [$customer->slug, $equipment->cust_equip_id]
            ),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
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

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 17)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.equipment.notes.store', [$customer->slug, $equipment->cust_equip_id]), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        // Event::fake();

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

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.equipment.notes.store', [$customer->slug, $equipment->cust_equip_id]), $data);

        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        $this->assertDatabaseHas('customer_notes', $data);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteCreatedEvent::class);
    }

    public function test_store_equipment_note()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.equipment.notes.store', [$customer->slug, $equipment->cust_equip_id]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        $this->assertDatabaseHas('customer_notes', $data);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteCreatedEvent::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.show', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.notes.show', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.equipment.notes.edit', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 18)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.notes.edit', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id
            ]));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.notes.edit', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id
            ]));
        $response->assertSuccessful();
    }

    /*
     *   Update Method
     */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
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
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
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

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 18)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.equipment.notes.update', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.equipment.notes.update', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);
        $this->assertDatabaseHas('customer_notes', $data);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteUpdatedEvent::class);
    }

    /*
     *   Destroy Function
     */
    public function test_destroy_guest()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->delete(route('customers.equipment.notes.destroy', [
            $customer->slug,
            $equipment->cust_equip_id,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        $this->assertDatabaseHas('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));
    }

    public function test_destroy_no_permission()
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 19)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.equipment.notes.destroy', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));
        $response->assertStatus(403);

    }

    public function test_destroy()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.equipment.notes.destroy', [
                $customer->slug,
                $equipment->cust_equip_id,
                $note->note_id,
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.note.deleted'));
        $this->assertSoftDeleted('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteDeletedEvent::class);
    }
}
