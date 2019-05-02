<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerDetailsTest extends TestCase
{
    public $cust;
    
    use RefreshDatabase;
    
    //  Populate a customer into the database
    public function setUp():void
    {
        Parent::setup();
        
        $this->cust = factory(Customers::class)->create();
    }
    
    //  Test visit customer details landing page as guest
    public function testLandingGuest()
    {
        $response = $this->get(route('customer.details', [$this->cust->cust_id, $this->cust->name]));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test visit customer details landing page as tech
    public function testLandingTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.details', [$this->cust->cust_id, $this->cust->name]));
        
        $response->assertSuccessful();
        $response->assertViewIs('customer.details');
    }
    
    //  Test visit the customer details page with a bad customer ID
    public function testLandingBadID()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.details', [958745487452, $this->cust->name]));
        
        $response->assertSuccessful();
        $response->assertViewIs('err.customerNotFound');
    }
    
    //  Test getting customer details as guest
    public function testGetDetailsGuest()
    {
        $response = $this->get(route('customer.id.show', $this->cust->cust_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test getting customer details as tech
    public function testGetDetailsTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.id.show', $this->cust->cust_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['cust_id', 'name', 'address', 'city', 'state', 'zip', 'active']);
    }
    
    //  Test marking the customer as a user fav as guest
    public function testBookmarkGuest()
    {
        $response = $this->get(route('customer.toggleFav', ['add', $this->cust->cust_id]));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test marking the customer as a user fav
    public function testBookmark()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.toggleFav', ['add', $this->cust->cust_id]));
        
        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);
    }
    
    //  Test update customer data as guest
    public function testUpdateDetailsGuest()
    {
        $data = [
            'name' => 'New Customer Name',
            'dba_name' => null,
            'address' => '555 Some Drive',
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];
        
        $response = $this->put(route('customer.id.update', $this->cust->cust_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test update customer as tech
    public function testUpdateDetailsTech()
    {
        $data = [
            'name' => 'New Customer Name',
            'dba_name' => null,
            'address' => '555 Some Drive',
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);
    }
    
    //  Test trying to nullify the customer name and address
    public function testNullifyName()
    {
        $data = [
            'name' => null,
            'dba_name' => null,
            'address' => null,
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name',
            'address'
        ]);
    }
}
