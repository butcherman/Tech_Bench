<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewCustomerTest extends TestCase
{
    public $cust;
    
    use RefreshDatabase;
    
    //  Verify that a guest cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('customer.id.create'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a logged in tech can visit the page
    public function testTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('customer.id.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('customer.newCustomer');
    }
    
    //  Verify that a guest cannot submit a new customer
    public function testSubmitGuest()
    {
        $custData = factory(Customers::class)->make();
        
        $data = [
            'custID'        => $custData->cust_id,
            'custName'      => $custData->name,
            'custDBA'       => $custData->dba_name,
            'custAddr'      => $custData->address,
            'custCity'      => $custData->city,
            'selectedState' => $custData->state,
            'custZip'       => $custData->zip
        ];
        
        $response = $this->post(route('customer.id.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech user can create a new customer
    public function testSubmitTech()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();
        
        $data = [
            'custID'        => $custData->cust_id,
            'custName'      => $custData->name,
            'custDBA'       => $custData->dba_name,
            'custAddr'      => $custData->address,
            'custCity'      => $custData->city,
            'selectedState' => $custData->state,
            'custZip'       => $custData->zip
        ];
        
        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['success', 'url']);
    }
    
    //  Verify that a duplicate customer ID cannot be created
    public function testDupID()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();
        
        $data = [
            'custID'        => $custData->cust_id,
            'custName'      => $custData->name,
            'custDBA'       => $custData->dba_name,
            'custAddr'      => $custData->address,
            'custCity'      => $custData->city,
            'selectedState' => $custData->state,
            'custZip'       => $custData->zip
        ];
        
        $cust2 = factory(Customers::class)->create([
            'cust_id' => $custData->cust_id
        ]);
        
        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['custID']);
    }
}
