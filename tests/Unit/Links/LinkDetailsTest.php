<?php

namespace Tests\Unit\Links;

use App\Customers;
use App\FileLinks;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkDetailsTest extends TestCase
{
    public $fileLink, $owner;
    
    use RefreshDatabase;
    
    public function setUp():void
    {
        Parent::setUp();
        
        $this->owner = $this->getTech();
        $this->fileLink  = factory(FileLinks::class)->create([
            'user_id' => $this->owner->user_id
        ]);
    }
    
    //  Verify that a guest cannot get the details of any link
    public function testGetLinkDetailsGuest()
    {
        $response = $this->get(route('links.data.show', $this->fileLink->link_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that the details of the link can be properly retrieved
    public function testGetLinkDetails()
    {
        $response = $this->actingAs($this->owner)->get(route('links.data.show', $this->fileLink->link_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['link_id', 'user_id', 'cust_id', 'link_hash', 'link_name', 'note', 'expire']);
    }
    
    //  Verify that a guest cannot update the link data
    public function testUpdateLinkDetailsGuest()
    {
        $data = [
            'name'         => 'Updated Link Name',
            'expire'       => date('Y-m-d', strtotime('+30 days')),
            'allow_upload' => 'on'
        ];
        
        $response = $this->put(route('links.data.update', $this->fileLink->link_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a user can update the link data
    public function testUpdateLinkDetails()
    {
        $data = [
            'name'         => 'Updated Link Name',
            'expire'       => date('Y-m-d', strtotime('+30 days')),
            'allow_upload' => 'on'
        ];
        
        $response = $this->actingAs($this->owner)->put(route('links.data.update', $this->fileLink->link_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Verify that a guest cannot update the links customer 
    public function testUpdateCustomerGuest()
    {
        $cust = factory(Customers::class)->create();
        
        $data = [
            'customer_tag' => $cust->cust_id.' - '.$cust->name
        ];
        
        $response = $this->post(route('links.updateCustomer', $this->fileLink->link_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a user can update the links customer 
    public function testUpdateCustomer()
    {
        $cust = factory(Customers::class)->create();
        
        $data = [
            'customer_tag' => $cust->cust_id.' - '.$cust->name
        ];
        
        $response = $this->actingAs($this->owner)->post(route('links.updateCustomer', $this->fileLink->link_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Verify that the instructions cannot be updated by guest
    public function testUpdateInstructionsGuest()
    {
        $data = [
            'note' => 'This is some custom instructions for the link'
        ];
        
        $response = $this->post(route('links.instructions', $this->fileLink->link_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that the instructions can be updated
    public function testUpdateInstructions()
    {
        $data = [
            'note' => 'This is some custom instructions for the link'
        ];
        
        $response = $this->actingAs($this->owner)->post(route('links.instructions', $this->fileLink->link_id), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Verify that a guest cannot get the updated instructions
    public function testGetInstructionsGuest()
    {
        $response = $this->get(route('links.instructions', $this->fileLink->link_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a user can get the updated instructions
    public function testGetInstructions()
    {
        $response = $this->actingAs($this->owner)->get(route('links.instructions', $this->fileLink->link_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['note']);
    }
    
    //  Verify that a guest cannot delete a link
    public function testDeleteLinkGuest()
    {
        $response = $this->delete(route('links.data.destroy', $this->fileLink->link_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that the link can be deleted
    public function testDeleteLink()
    {
        $response = $this->actingAs($this->owner)->delete(route('links.data.destroy', $this->fileLink->link_id));
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
