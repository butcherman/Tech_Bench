<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerNote;

class CustomerNoteTest extends TestCase
{
    /*
    *   Store method
    */
    public function test_store_guest()
    {
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'subect'  => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared'  => false,
            'urgent'  => true,
        ];

        $response = $this->post(route('customers.notes.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store()
    {
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared'  => false,
            'urgent'  => true,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.notes.store'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('customer_notes', $data);
    }

    /*
    *   Show Method
    */
    public function test_show_guest()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->get(route('customers.notes.show', $note->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_show()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.notes.show', $note->cust_id));
        $response->assertSuccessful();
        $response->assertJson([$note->toArray()]);
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
            'shared'  => false,
            'urgent'  => true,
        ];

        $response = $this->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update()
    {
        $note = CustomerNote::factory()->create();
        $data = [
            'cust_id' => $note->cust_id,
            'subject' => 'This is a test Note',
            'details' => 'This is the notes details',
            'shared'  => false,
            'urgent'  => true,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.notes.update', $note->note_id), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('customer_notes', $data);
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

        $this->assertDatabaseHas('customer_notes', $note->only(['note_id', 'subject', 'details']));
    }

    public function test_destroy()
    {
        $note = CustomerNote::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.notes.destroy', $note->note_id));
        $response->assertSuccessful();
        $this->assertSoftDeleted('customer_notes', $note->only(['note_id', 'subject', 'details']));
    }
}
