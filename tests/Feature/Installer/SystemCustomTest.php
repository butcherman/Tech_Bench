<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemCustomTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('installer.customize'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('installer.customize'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('installer.customize'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user canot visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('installer.customize'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('installer.customize'));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.customize');
    }
    
    //  Verify unauthorized user cannot submit the timezone update form
    public function testGuestSubmit()
    {
        $data = [
            'timezone' => 'Atlantic/Azores'
        ];
        
        $response = $this->post(route('installer.customize'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot submit the timezone update form
    public function testTechSubmit()
    {
        $user = $this->getTech();
            
        $data = [
            'timezone' => 'Atlantic/Azores'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.customize'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can submit the timezone update form
    public function testReportSubmit()
    {
        $user = $this->getReport();
            
        $data = [
            'timezone' => 'Atlantic/Azores'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.customize'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user canot submit the timezone update form
    public function testAdminSubmit()
    {
        $user = $this->getAdmin();
            
        $data = [
            'timezone' => 'Atlantic/Azores'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.customize'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Installer user can submit the timezone update form
    public function testInstallerSubmit()
    {
        $user = $this->getInstaller();
            
        $data = [
            'timezone' => 'Atlantic/Azores'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.customize'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'Timezone Successfully Updated']);
    }
}
