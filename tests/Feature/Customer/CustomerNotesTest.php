<?php

namespace Tests\Feature;

use App\Customers;
use Tests\TestCase;
use App\CustomerNotes;

class CustomerNotesTest extends TestCase
{
    public $cust, $note;

    //  Populate a note into the database
    public function setUp(): void
    {
        Parent::setup();

        $this->cust = factory(Customers::class)->create();
        $this->note = factory(CustomerNotes::class)->create([
            'cust_id' => $this->cust->cust_id
        ]);
    }

    //  Test getting notes as guest
    public function test_get_notes_as_guest()
    {
        $response = $this->get(route('customer.notes.show', $this->cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test Getting notes as tech
    public function test_get_notes()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.notes.show', $this->cust->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['cust_id', 'note_id', 'description', 'subject']]);
    }

    //  Test get notes when the parent has notes too
    public function test_get_notes_from_parent()
    {
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $notes = factory(CustomerNotes::class, 3)->create([
            'cust_id' => $this->cust->cust_id,
            'shared'  => 1,
        ]);

        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.notes.show', $child->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['cust_id', 'note_id', 'description', 'subject']]);
        $response->assertJsonCount(3);
    }

    //  Test adding notes as a guest
    public function test_add_note_as_guest()
    {
        $data = [
            'cust_id'     => $this->cust->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => false,
        ];

        $response = $this->post(route('customer.notes.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test adding note as tech
    public function test_add_note()
    {
        $user = $this->getTech();
        $data = [
            'cust_id'     => $this->cust->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => false,
        ];

        $response = $this->actingAs($user)->post(route('customer.notes.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test adding note as tech to a child customer
    public function test_add_note_to_child()
    {
        $user = $this->getTech();
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $data = [
            'cust_id'     => $child->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => true,
        ];

        $response = $this->actingAs($user)->post(route('customer.notes.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test adding a note with validation errors
    public function test_add_note_no_data()
    {
        $user = $this->getTech();
        $data = [
            'cust_id'     => null,
            'subject'     => null,
            'description' => null,
            'urgent'      => null,
            'shared'      => null,
        ];

        $response = $this->actingAs($user)->post(route('customer.notes.store'), $data);

        $response->assertStatus(302);
    }

    //  Test updating note as guest
    public function test_update_note_as_guest()
    {
        $data = [
            'note_id'     => $this->note->note_id,
            'cust_id'     => $this->cust->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => false,
        ];

        $response = $this->put(route('customer.notes.update', $this->note->note_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test updating note as Tech
    public function test_update_note()
    {
        $user = $this->getTech();
        $data = [
            'note_id'     => $this->note->note_id,
            'cust_id'     => $this->cust->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => false,
        ];

        $response = $this->actingAs($user)->put(route('customer.notes.update', $this->note->note_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test updating note by linking to parent
    public function test_update_note_link_to_parent()
    {
        $user = $this->getTech();
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $note = factory(CustomerNotes::class)->create([
            'cust_id' => $child->cust_id
        ]);
        $data = [
            'note_id'     => $this->note->note_id,
            'cust_id'     => $child->cust_id,
            'subject'     => 'Here is a new note',
            'description' => 'Here is some note content',
            'urgent'      => true,
            'shared'      => true,
        ];

        $response = $this->actingAs($user)->put(route('customer.notes.update', $note->note_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test editing a note with validation errors
    public function test_edit_note_no_data()
    {
        $user = $this->getTech();
        $data = [
            'note_id'     => $this->note->note_id,
            'cust_id'     => null,
            'subject'     => null,
            'description' => null,
            'urgent'      => true,
            'shared'      => true,
        ];

        $response = $this->actingAs($user)->put(route('customer.notes.update', $this->note->note_id), $data);

        $response->assertStatus(302);
    }

    //  Test deleting note as Guest
    public function test_delete_note_as_guest()
    {
        $response = $this->delete(route('customer.notes.destroy', $this->note->note_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test deleting contact as Tech
    public function test_delete_note()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.notes.destroy', $this->note->note_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
