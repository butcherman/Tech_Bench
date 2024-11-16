<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerNoteTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.notes.index', $customer->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.notes.index', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Index')
                ->has('permissions')
                ->has('customer')
                ->has('notes')
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

        $response = $this->get(
            route('customers.notes.create', $customer->slug)
        );

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

        $response = $this->actingAs($user)
            ->get(route('customers.notes.create', $customer->slug));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.notes.create', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Create')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('equipmentList')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        // Remove the 'Add Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.notes.store', $customer->slug), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.notes.store', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);

        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_store_site_note(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => $customer->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.notes.store', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.created'));

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
    }

    public function test_store_equipment_note(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'equipment',
            'urgent' => true,
            'site_list' => [],
            'details' => 'This is the notes details',
            'cust_equip_id' => CustomerEquipment::factory()
                ->create(['cust_id' => $customer->cust_id])
                ->cust_equip_id,
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.notes.store', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.created'));

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);

        $this->assertDatabaseHas('customer_notes', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.notes.show', [
            $customer->slug,
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
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.notes.show', [
                $customer->slug,
                $note->note_id,
            ]));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Note/Show')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('note')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->get(route('customers.notes.edit', [
            $customer->slug,
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
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.notes.edit', [
                $customer->slug,
                $note->note_id,
            ]));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.notes.edit', [
                $customer->slug,
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
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        // Remove the 'Edit Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
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

        $response = $this->actingAs($user)
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
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

        $response = $this->actingAs($user)
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);
        $data['note_id'] = $note->note_id;

        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_update_sites(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
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
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.notes.update', [
                $customer->slug,
                $note->note_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.updated'));

        unset($data['site_list']);
        unset($data['note_type']);
        $data['note_id'] = $note->note_id;

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
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Function
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->delete(route('customers.notes.destroy', [
            $customer->slug,
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

    public function test_destroy_no_permission(): void
    {
        // Remove the 'Delete Customer Note' permission from the Tech Role
        $this->changeRolePermission(4, 'Delete Customer Note', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $this->changeRolePermission(4, 'Delete Customer Note');

        $response = $this->actingAs($user)
            ->delete(route('customers.notes.destroy', [
                $customer->slug,
                $note->note_id,
            ]));

        $response->assertForbidden();

    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.notes.destroy', [
                $customer->slug,
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

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    public function test_restore_guest(): void
    {
        $note = CustomerNote::factory()->create();

        $response = $this->get(route('customers.deleted-items.restore.notes', [
            $note->cust_id,
            $note->note_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertForbidden();
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.note.restored'));

        $this->assertDatabaseHas('customer_notes', $note->only([
            'note_id',
        ]));
    }

    /*
    |---------------------------------------------------------------------------
    | Force Delete Method
    |---------------------------------------------------------------------------
    */
    public function test_force_delete_guest(): void
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->delete(
            route('customers.deleted-items.force-delete.notes',
                [
                    $note->cust_id,
                    $note->note_id,
                ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertForbidden();
    }

    public function test_force_delete(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $note = CustomerNote::factory()->create();
        $note->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.notes', [
                $note->cust_id,
                $note->note_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.note.force_deleted'));

        $this->assertDatabaseMissing('customer_notes', $note->only(['note_id']));
    }
}
