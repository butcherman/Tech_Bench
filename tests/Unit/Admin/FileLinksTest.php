<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileLinksTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify unauthorized user cannot pull data
    public function testGuest()
    {
        $response = $this->get(route('admin.countLinks'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot pull data
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('admin.countLinks'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user cannot pull data
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('admin.countLinks'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user can pull data
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('admin.countLinks'));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'total', 'expired']]);
    }
    
    //  Verify that an Installer user can pull data
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('admin.countLinks'));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'total', 'expired']]);
    }
}
