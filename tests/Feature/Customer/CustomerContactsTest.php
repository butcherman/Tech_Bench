<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use App\CustomerContacts;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContactsTest extends TestCase
{
    public $cust, $cont;
    
    use RefreshDatabase;
    
    //  Populate a customer into the database
    public function setUp():void
    {
        Parent::setup();
        
        $this->cust = factory(Customers::class)->create();
        $this->cont = factory(CustomerContacts::class)->create([
            'cust_id' => $this->cust->cust_id
        ]);
    }
    
    //  Test getting contacts as guest
    public function testGetContactsGuest()
    {
        $response = $this->get(route('customer.contacts.show', $this->cust->cust_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test Getting contacts as tech
    public function testGetContactsTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.contacts.show', $this->cust->cust_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['cont_id', 'name', 'numbers', 'email']]);
    }
    
    //  Test adding contact as a guest
    public function testAddContactGuest()
    {
        $data = [
            'custID' => $this->cust->cust_id,
            'name' => 'Mickey Mouse',
            'email' => 'mickey@mouse.email',
        ];
        
        $response = $this->post(route('customer.contacts.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test adding contact as tech
    public function testAddContactTech()
    {
        $user = $this->getTech();
        $data = [
            'custID'  => $this->cust->cust_id,
            'name'    => 'Mickey Mouse',
            'email'   => 'mickey@mouse.email',
            'numbers' => [
                'type'   => [2],
                'number' => [5306654744],
                'ext'    => null
            ]
        ];
        
        $response = $this->actingAs($user)->post(route('customer.contacts.store'), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Test updating contact as guest
    public function testUpdateContactGuest()
    {
        $data = [
            'custID' => $this->cust->cust_id,
            'name' => 'Jimmy Mouse',
            'email' => 'jimmy@mouse.email',
        ];
        
        $response = $this->put(route('customer.contacts.update', $this->cont->cont_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test updating contact as Tech
    public function testUpdateContactTech()
    {
        $user = $this->getTech();
        $data = [
            'custID' => $this->cust->cust_id,
            'name'   => 'Jimmy Mouse',
            'email'  => 'jimmy@mouse.email',
            'numbers' => [
                'type'   => [2],
                'number' => [5306654744],
                'ext'    => null
            ]
        ];
        
        $response = $this->actingAs($user)->put(route('customer.contacts.update', $this->cont->cont_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Test deleting contact as Guest
    public function testDeleteContactGuest()
    {
        $response = $this->delete(route('customer.contacts.destroy', $this->cont->cont_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test deleting contact as Tech
    public function testDeleteContactTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.contacts.destroy', $this->cont->cont_id));
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
