<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallerUserSettingsTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('installer.userSecurity'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('installer.userSecurity'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('installer.userSecurity'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user canot visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('installer.userSecurity'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('installer.userSecurity'));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.userSecurity');
    }
    
    //  Verify that a guest cannot submit the user settings form
    public function submitAsGuest()
    {
        $data = [
            'passExpire' => 365
        ];
        
        $this->post(route('installer.userSecurity'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot submit the user settings form
    public function submitAsTech()
    {
        $user = $this->getTech();
        $data = [
            'passExpire' => 365
        ];
        
        $this->actingAs($user)->post(route('installer.userSecurity'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot submit the user settings form
    public function submitAsReport()
    {
        $user = $this->getReport();
        $data = [
            'passExpire' => 365
        ];
        
        $this->actingAs($user)->post(route('installer.userSecurity'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot submit the user settings form
    public function submitAsAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'passExpire' => 365
        ];
        
        $this->actingAs($user)->post(route('installer.userSecurity'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can submit the user settings form
    public function submitAsInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'passExpire' => 365
        ];
        
        $this->actingAs($user)->post(route('installer.userSecurity'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'User Security Updated']);
    }
}
