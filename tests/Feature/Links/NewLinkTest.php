<?php

namespace Tests\Feature\Links;

use App\Customers;
use Tests\TestCase;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewLinkTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('links.data.create'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user can visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('links.data.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.form.newLink');
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('links.data.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.form.newLink');
    }
    
    //  Verify that an Admin user can visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('links.data.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.form.newLink');
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('links.data.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.form.newLink');
    }
    
    //  Verify form submitted with all but file works 
    public function testSubmitNoFile()
    {
        $user = $this->getTech();
        $customer = factory(Customers::class)->create();
        $data = [
            'cust_id'    => $customer->cust_id,
            'name'       => 'This is a name',
            'expire'     => date('Y-m-d', strtotime('+30 days')),
            '_completed' => true
        ];
        
        $response = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['link', 'name']);
    }
    
    //  Verify form submitted with a file works
    public function testSubmitWithFile()
    {
        Storage::fake(config('filesystems.paths.links'));
        
        $user = $this->getTech();
        $customer = factory(Customers::class)->create();
        $data = [
            'cust_id' => $customer->cust_id,
            'name'    => 'This is a name',
            'expire'  => date('Y-m-d', strtotime('+30 days')),
            'file'    => $file = UploadedFile::fake()->image('random.jpg')
        ];
        
        $response1 = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data);
        $response1->assertSuccessful();
        
        $data2 = [
            'cust_id' => $customer->cust_id,
            'name'    => 'This is a name',
            'expire'  => date('Y-m-d', strtotime('+30 days')),
            '_completed' => true
        ];
        
        $response2 = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data2);
        
        $response2->assertSuccessful();
        $response2->assertJsonStructure(['link', 'name']);
    }
    
    //  Verify form submitted with no customer link 
    public function testSubmitNoCustomer()
    {
        $user = $this->getTech();
        $data = [
            'name'       => 'This is a name',
            'expire'     => date('Y-m-d', strtotime('+30 days')),
            '_completed' => true
        ];
        
        $response = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['link', 'name']);
    }
    
    //  Verify form submitted with no name
    public function testSubmitNoName()
    {
        $user = $this->getTech();
        $data = [
            'expire'    => date('Y-m-d', strtotime('+30 days'))
        ];
        
        $response = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }
    
    //  Verify form submitted with no expire date
    public function testSubmitNoExpire()
    {
        $user = $this->getTech();
        $data = [
            'name' => 'This is a name',
        ];
        
        $response = $this->actingAs($user)->from(route('links.data.create'))->post(route('links.data.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors('expire');
    }
}
