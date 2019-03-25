<?php

namespace Tests\Unit\Links;

use App\Customers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewLinkTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify page cannot be accessed without valid session
    public function testGuestAccess()
    {
        $customer = factory(Customers::class)->create();
        
        $response = $this->get(route('customer.searchID', $customer->cust_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify JSON data returned from Customer Search route - no customer data present
    public function testCustomerSearchNoData()
    {
        $customer = factory(Customers::class, 5)->create();
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('customer.searchID', 'NULL'));
        
        $response->assertSuccessful();
        $response->assertJsonCount(5);
    }
    
    //  Verify JSON data returned from Customer Search route - some customer data present
    public function testCustomerSearch()
    {
        $user = $this->getTech();
        $customer = factory(Customers::class)->create([
            'name' => 'a customer name'
        ]);
        
        $response = $this->actingAs($user)->get(route('customer.searchID', 'name'));
        
        $response->assertSuccessful();
        $response->assertJsonCount(1);
    }
}
