<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerNoteEvent;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerNoteSiteTest extends TestCase
{
    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.site.notes.create', [$customer->slug, $site->site_slug]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 17)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.site.notes.create', [$customer->slug, $site->site_slug]));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.site.notes.create', [$customer->slug, $site->site_slug]));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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
                'customers.site.notes.store',
                [$customer->slug, $site->site_slug]
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
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('customers.site.notes.store', [$customer->slug, $site->site_slug]), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('customers.site.notes.store', [$customer->slug, $site->site_slug]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        $this->assertDatabaseHas('customer_notes', $data);

        Event::assertDispatched(CustomerNoteEvent::class);
    }

    public function test_store_site_note()
    {
        Event::fake();

        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => $customer->CustomerSite->pluck('cust_site_id'),
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('customers.site.notes.store', [$customer->slug, $customer->CustomerSite[0]->site_slug]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
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

        Event::assertDispatched(CustomerNoteEvent::class);
    }

    public function test_store_equipment_note()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('customers.site.notes.store', [$customer->slug, $site->site_slug]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        $this->assertDatabaseHas('customer_notes', $data);

        Event::assertDispatched(CustomerNoteEvent::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.site.notes.show', [
            $customer->slug,
            $site->site_slug,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.site.notes.show', [
                $customer->slug,
                $site->site_slug,
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
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.site.notes.edit', [
            $customer->slug,
            $site->site_slug,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 18)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.site.notes.edit', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.site.notes.edit', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]));
        $response->assertSuccessful();
    }

    /*
     *   Update Method
     */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->put(route('customers.site.notes.update', [
            $customer->slug,
            $site->site_slug,
            $note->note_id,
        ]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('customers.site.notes.update', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
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

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('customers.site.notes.update', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);

        $this->assertDatabaseHas('customer_notes', $data);

        Event::assertDispatched(CustomerNoteEvent::class);
    }

    public function test_update_sites()
    {
        Event::fake();

        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $note->CustomerSite()->sync([
            $customer->CustomerSite[0]->cust_site_id,
            $customer->CustomerSite[1]->cust_site_id,
        ]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => [
                $customer->CustomerSite[1]->cust_site_id,
                $customer->CustomerSite[2]->cust_site_id,
            ],
            'cust_equip_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('customers.site.notes.update', [
                $customer->slug,
                $customer->CustomerSite[0]->site_slug,
                $note->note_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);

        $this->assertDatabaseHas('customer_notes', $data);
        $this->assertDatabaseMissing('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[0]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->CustomerSite[2]->cust_site_id,
        ]);

        Event::assertDispatched(CustomerNoteEvent::class);
    }

    /*
     *   Destroy Function
     */
    public function test_destroy_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->delete(route('customers.site.notes.destroy', [
            $customer->slug,
            $site->site_slug,
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
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 19)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->delete(route('customers.site.notes.destroy', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]));
        $response->assertStatus(403);

    }

    public function test_destroy()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->delete(route('customers.site.notes.destroy', [
                $customer->slug,
                $site->site_slug,
                $note->note_id,
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.note.deleted'));
        $this->assertSoftDeleted('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));

        Event::assertDispatched(CustomerNoteEvent::class);
    }
}
