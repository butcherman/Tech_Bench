<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use App\Models\UserRolePermissions;
use Tests\TestCase;

class CustomerNoteTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        $response = $this->post(route('customers.notes.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 17)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->post(route('customers.notes.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.notes.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));
        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_store_to_parent()
    {
        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => true,
            'urgent' => true,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.notes.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.created'));
        $this->assertDatabaseHas('customer_notes', [
            'cust_id' => $cust->parent_id,
            'subject' => $data['subject'],
            'details' => $data['details'],
            'shared' => $data['shared'],
            'urgent' => $data['urgent'],
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->get(route('customers.notes.show', [$note->Customer->slug, $note->note_id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.notes.show', [$note->Customer->slug, $note->note_id]));
        $response->assertSuccessful();
    }

    /*
    *   Update Method
    */
    public function test_update_guest()
    {
        $note = CustomerNote::factory()->create();
        $data = [
            'cust_id' => $note->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        $response = $this->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $note = CustomerNote::factory()->create();
        $data = [
            'cust_id' => $note->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 18)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $note = CustomerNote::factory()->create();
        $data = [
            'cust_id' => $note->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => false,
            'urgent' => true,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));
        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_update_to_parent()
    {
        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $cust->cust_id]);
        $data = [
            'cust_id' => $note->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared' => true,
            'urgent' => true,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.updated'));
        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $note->note_id,
            'cust_id' => $cust->parent_id,
            'subject' => $data['subject'],
            'details' => $data['details'],
            'shared' => $data['shared'],
            'urgent' => $data['urgent'],
        ]);
    }

    /*
    *   Destroy Function
    */
    public function test_destroy_guest()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->delete(route('customers.notes.destroy', $note->note_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();

        $this->assertDatabaseHas('customer_notes', $note->only(['note_id', 'subject', 'details']));
    }

    public function test_destroy_no_permission()
    {
        $note = CustomerNote::factory()->create();

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 19)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.notes.destroy', $note->note_id));
        $response->assertStatus(403);

    }

    public function test_destroy()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.notes.destroy', $note->note_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', __('cust.note.deleted'));
        $this->assertSoftDeleted('customer_notes', $note->only(['note_id', 'subject', 'details']));
    }

    /*
    *   Restore Function
    */
    public function test_restore_guest()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->get(route('customers.notes.restore', $note->note_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.notes.restore', $note->note_id));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.notes.restore', $note->note_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.note.restored'));
        $this->assertDatabaseHas('customer_notes', $note->only(['note_id', 'subject', 'details']));
    }

    /*
    *   Force Delete Method
    */
    public function test_force_delete_guest()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->delete(route('customers.notes.force-delete', $note->note_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.notes.force-delete', $note->note_id));
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $note = CustomerNote::factory()->create();
        $note->delete();
        $note->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.notes.force-delete', $note->note_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', __('cust.note.force_deleted'));
        $this->assertDatabaseMissing('customer_notes', $note->only(['note_id']));
    }
}
