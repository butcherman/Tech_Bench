<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminIndexTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('admin.index'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('admin.index'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user cannot visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('admin.index'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user can visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('admin.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('admin.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }
}
