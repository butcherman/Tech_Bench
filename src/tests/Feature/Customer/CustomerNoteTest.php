<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerNoteTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.notes.index', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.index', $customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.notes.create', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $customer = Customer::factory()->create();

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 17)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.create', $customer->slug));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.create', $customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->post(
            route(
                'customers.notes.store',
                $customer->slug
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
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 17)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.notes.store', $customer->slug), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.notes.store', $customer->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);
        $this->assertDatabaseHas('customer_notes', $data);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteCreatedEvent::class);
    }

    public function test_store_site_note()
    {
        // Event::fake();

        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => $customer->CustomerSite->pluck('cust_site_id'),
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.notes.store', $customer->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);
        $this->assertDatabaseHas('customer_notes', $data);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[0]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[2]->cust_site_id,
        ]);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteCreatedEvent::class);
    }

    public function test_store_equipment_note()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $notement = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => $notement->note_id,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.notes.store', $customer->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);
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
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.notes.show', [
            $customer->slug,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.show', [
                $customer->slug,
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
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.notes.edit', [
            $customer->slug,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 18)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.edit', [
                $customer->slug,
                $note->note_id
            ]));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.notes.edit', [
                $customer->slug,
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
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->put(route('customers.notes.update', [
            $customer->slug,
            $note->note_id,
        ]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 18)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);
        $data['note_id'] = $note->note_id;
        $this->assertDatabaseHas('customer_notes', $data);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteUpdatedEvent::class);
    }

    public function test_update_sites()
    {
        // Event::fake();

        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $note->CustomerSite()->sync([
            $customer->CustomerSite[0]->cust_site_id,
            $customer->CustomerSite[1]->cust_site_id
        ]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => [
                $customer->CustomerSite[1]->cust_site_id,
                $customer->CustomerSite[2]->cust_site_id
            ],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);
        $data['note_id'] = $note->note_id;
        $this->assertDatabaseHas('customer_notes', $data);
        $this->assertDatabaseMissing('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[0]->cust_site_id
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[1]->cust_site_id
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[2]->cust_site_id
        ]);

        // TODO - Dispatch Event
        // Event::assertDispatched(CustomerNoteUpdatedEvent::class);
    }

    /*
     *   Destroy Function
     */
    public function test_destroy_guest()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->delete(route('customers.notes.destroy', [
            $customer->slug,
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
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 19)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.notes.destroy', [
                $customer->slug,
                $note->note_id,
            ]));
        $response->assertStatus(403);

    }

    public function test_destroy()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.notes.destroy', [
                $customer->slug,
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

    /**
     * Restore Method
     */
    public function test_restore_guest()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->get(route('customers.deleted-items.restore.notes', [
            $note->cust_id,
            $note->note_id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.deleted-items.restore.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.deleted-items.restore.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.restored'));
        $this->assertDatabaseHas('customer_notes', $note->only([
            'note_id'
        ]));
    }

    /**
     * Force Delete Method
     */
    public function test_force_delete_guest()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->delete(route('customers.deleted-items.force-delete.notes', [
            $note->cust_id,
            $note->note_id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.deleted-items.force-delete.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.deleted-items.force-delete.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.note.force_deleted'));
        $this->assertDatabaseMissing('customer_notes', $note->only(['note_id']));
    }
}
