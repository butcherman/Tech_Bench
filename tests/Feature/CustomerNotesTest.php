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

    //  Test adding notes as a guest
    public function test_add_note_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'title'   => 'Here is a new note',
            'note'    => 'Here is some note content',
            'urgent'  => true
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
            'cust_id' => $this->cust->cust_id,
            'title'   => 'Here is a new note',
            'note'    => 'Here is some note content',
            'urgent'  => true
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
            'cust_id' => null,
            'title'   => null,
            'note'    => null,
            'urgent'  => null
        ];

        $response = $this->actingAs($user)->post(route('customer.notes.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('title', 'note');
    }

    //  Test updating note as guest
    public function test_update_note_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'title'   => 'Here is a new note',
            'note'    => 'Here is some note content',
            'urgent'  => true
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
            'cust_id' => $this->cust->cust_id,
            'title'   => 'Here is a new note',
            'note'    => 'Here is some note content',
            'urgent'  => true
        ];

        $response = $this->actingAs($user)->put(route('customer.notes.update', $this->note->note_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test editing a note with validation errors
    public function test_edit_note_no_data()
    {
        $user = $this->getTech();
        $data = [
            'cust_id' => null,
            'title'   => null,
            'note'    => null,
            'urgent'  => null
        ];

        $response = $this->actingAs($user)->put(route('customer.notes.update', $this->note->note_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('title', 'note');
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
