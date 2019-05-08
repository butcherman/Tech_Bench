<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use App\CustomerNotes;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerNotesTest extends TestCase
{
    public $cust, $note;
    
    use RefreshDatabase;
    
    //  Populate a note into the database
    public function setUp():void
    {
        Parent::setup();
        
        $this->cust = factory(Customers::class)->create();
        $this->note = factory(CustomerNotes::class)->create([
            'cust_id' => $this->cust->cust_id
        ]);
    }
    
    //  Test getting notes as guest
    public function testGetNotesGuest()
    {
        $response = $this->get(route('customer.notes.show', $this->cust->cust_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test Getting notes as tech
    public function testGetNotesTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.notes.show', $this->cust->cust_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['cust_id', 'note_id', 'description', 'subject']]);
    }
    
    //  Test adding notes as a guest
    public function testAddNoteGuest()
    {
        $data = [
            'custID' => $this->cust->cust_id,
            'title' => 'Here is a new note',
            'note' => 'Here is some note content',
            'urgent' => 'urgent'
        ];
        
        $response = $this->post(route('customer.notes.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test adding note as tech
    public function testAddNoteTech()
    {
        $user = $this->getTech();
        $data = [
            'custID' => $this->cust->cust_id,
            'title' => 'Here is a new note',
            'note' => 'Here is some note content',
            'urgent' => 'urgent'
        ];
        
        $response = $this->actingAs($user)->post(route('customer.notes.store'), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Test updating note as guest
    public function testUpdateNoteGuest()
    {
        $data = [
            'custID' => $this->cust->cust_id,
            'title' => 'Here is a new note',
            'note' => 'Here is some note content',
            'urgent' => 'urgent'
        ];
        
        $response = $this->put(route('customer.notes.update', $this->note->note_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test updating note as Tech
    public function testUpdateNoteTech()
    {
        $user = $this->getTech();
        $data = [
            'custID' => $this->cust->cust_id,
            'title' => 'Here is a new note',
            'note' => 'Here is some note content',
            'urgent' => 'urgent'
        ];
        
        $response = $this->actingAs($user)->put(route('customer.notes.update', $this->note->note_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Test deleting note as Guest
    public function testDeleteNoteGuest()
    {
        $response = $this->delete(route('customer.notes.destroy', $this->note->note_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test deleting contact as Tech
    public function testDeleteNoteTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.notes.destroy', $this->note->note_id));
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
