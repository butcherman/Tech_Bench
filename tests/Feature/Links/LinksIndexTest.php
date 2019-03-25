<?php

namespace Tests\Feature\Links;

use App\FileLinks;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinksIndexTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('links.index'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user can visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('links.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('links.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }
    
    //  Verify that an Admin user can visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('links.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('links.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }
}
