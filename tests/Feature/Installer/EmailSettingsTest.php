<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailSettingsTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('installer.emailSettings'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('installer.emailSettings'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('installer.emailSettings'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user canot visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('installer.emailSettings'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('installer.emailSettings'));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.emailSettings');
    }
    
    //  Verify unauthorized user cannot submit the email update form
    public function testGuestSubmit()
    {
        $data = [
            'host'       => 'newhost.email.com',
            'port'       => '4545',
            'encryption' => 'tls',
            'username'   => 'newName',
            'password'   => 'newPass'
        ];
        
        $response = $this->post(route('installer.emailSettings'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot submit the email update form
    public function testTechSubmit()
    {
        $user = $this->getTech();
            
        $data = [
            'host'       => 'newhost.email.com',
            'port'       => '4545',
            'encryption' => 'tls',
            'username'   => 'newName',
            'password'   => 'newPass'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.emailSettings'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can submit the email update form
    public function testReportSubmit()
    {
        $user = $this->getReport();
            
        $data = [
            'host'       => 'newhost.email.com',
            'port'       => '4545',
            'encryption' => 'tls',
            'username'   => 'newName',
            'password'   => 'newPass'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.emailSettings'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user canot submit the email update form
    public function testAdminSubmit()
    {
        $user = $this->getAdmin();
            
        $data = [
            'host'       => 'newhost.email.com',
            'port'       => '4545',
            'encryption' => 'tls',
            'username'   => 'newName',
            'password'   => 'newPass'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.emailSettings'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Installer user can submit the email update form
    public function testInstallerSubmit()
    {
        $user = $this->getInstaller();
            
        $data = [
            'host'       => 'newhost.email.com',
            'port'       => '4545',
            'encryption' => 'tls',
            'username'   => 'newName',
            'password'   => 'newPass'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.emailSettings'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'Tech Bench Successfully Updated']);
    }
}
