<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerIndexTest extends TestCase
{
    public $cust;
    
    use RefreshDatabase;
    
    //  Populate some customers in the database
    public function setUp():void
    {
        Parent::setup();
        
        $this->cust = factory(Customers::class, 5)->create();
    }
    
    //  Verify that a guest cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('customer.index'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a logged in tech can visit the page
    public function testTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('customer.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('customer.index');
    }
    
    //  Verify that a guest cannot pull a list of customers
    public function testCustListGuest()
    {
        $response = $this->get(route('customer.search'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a logged in tech can pull a list of customers
    public function testCustListTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('customer.search'));
                
        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'city', 'url', 'sys']]);
    }
}
