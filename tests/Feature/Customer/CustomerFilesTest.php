<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use App\CustomerFiles;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerFilesTest extends TestCase
{
    public $cust, $file;
    
    use RefreshDatabase;
    
    //  populate a customer and file into the database
    public function setUp():void
    {
        Parent::setup();
        
        $this->cust = factory(Customers::class)->create();
        $this->file = factory(CustomerFiles::class)->create([
            'cust_id' => $this->cust->cust_id
        ]);
    }
    
    //  test getting files as guest
    public function testGetFilesGuest()
    {
        $response = $this->get(route('customer.files.show', $this->cust->cust_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  test getting files as tech
    public function testGetFilesTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.files.show', $this->cust->cust_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'type', 'first_name', 'last_name', 'added_on', 'file_id', 'file_name', 'cust_file_id']]);
    }
    
    //  Test add file as guest
    public function testAddFileGuest()
    {
        $data = [
            'custID' => $this->cust->cust_id,
            'name'   => 'Test File Description',
            'type'   => $this->file->file_type_id
        ];
            
        $response = $this->post(route('customer.files.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test add file as tech
//    public function testAddFileTech()
//    {
//        $user = $this->getTech();
//        $data = [
//            'custID' => $this->cust->cust_id,
//            'name'   => 'Test File Description',
//            'type'   => $this->file->file_type_id
//        ];
//            
//        $response = $this->actingAs($user)->post(route('customer.files.store'), $data);
//                
//        $response->assertSuccessful();
//        $response->assertJson(['success' => true]);
//    }
    
    //  Test deleting file as guest
    public function testDeleteFileGuest()
    {
        $response = $this->delete(route('customer.files.destroy', $this->file->cust_file_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test deleting a file as tech
    public function testDeleteFileTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.files.destroy', $this->file->cust_file_id));
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
