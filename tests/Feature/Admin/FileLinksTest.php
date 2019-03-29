<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileLinksTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify unauthorized user cannot visit the user list page
    public function testGuest()
    {
        $response = $this->get(route('admin.links'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the user list page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('admin.links'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user cannot visit the user list page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('admin.links'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user can visit the user list page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('admin.links'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.userLinks');
    }
    
    //  Verify that an Installer user can visit the user list page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('admin.links'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.userLinks');
    }
    
    ////////////////////////////////////////////////////////////////////////////////
    
    //  Verify unauthorized user cannot visit the user details page
    public function testDetailsGuest()
    {
        $response = $this->get(route('admin.userLinks', 1));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the user list page
    public function testDetailsTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('admin.userLinks', 1));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user cannot visit the user details page
    public function testDetailsReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('admin.userLinks', 1));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user can visit the user details page
    public function testDetailsAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('admin.userLinks', 1));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.linkDetails');
    }
    
    //  Verify that an Installer user can visit the user details page
    public function testDetailsInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('admin.userLinks', 1));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.linkDetails');
    }
}
