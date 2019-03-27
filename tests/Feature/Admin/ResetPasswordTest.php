<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    public $user;
    
    use RefreshDatabase;
    
    public function setUp():void
    {
        Parent::setUp();
        
        $this->user  = $this->getTech();
    }
    
    //  Test viewing the edit form as guest
    public function testViewFormGuest()
    {
        $response = $this->get(route('admin.changePassword', $this->user->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test submitting the edit form as guest
    public function testSubmitFormGuest()
    {
        $data = [
            'password'           => 'NewPassword',
            'password_confirmed' => 'NewPassword'
        ];
        
        $response = $this->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test viewing the edit form as tech
    public function testViewFormTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('admin.changePassword', $this->user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Test submitting the edit form as tech
    public function testSubmitFormTech()
    {
        $user = $this->getTech();
        $data = [
            'password'           => 'NewPassword',
            'password_confirmed' => 'NewPassword'
        ];
        
        $response = $this->actingAs($user)->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Test viewing the edit form as Report user
    public function testViewFormReport()
    {
        $user = $this->getReport();
        
        $response = $this->actingAs($user)->get(route('admin.changePassword', $this->user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Test submitting the edit form as Report User
    public function testSubmitFormReport()
    {
        $user = $this->getReport();
        $data = [
            'password'           => 'NewPassword',
            'password_confirmed' => 'NewPassword'
        ];
        
        $response = $this->actingAs($user)->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Test viewing the edit form as Admin
    public function testViewFormAdmin()
    {
        $user = $this->getAdmin();
        
        $response = $this->actingAs($user)->get(route('admin.changePassword', $this->user->user_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.changePassword');
    }
    
    //  Test submitting the edit form as Admin
    public function testSubmitFormAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'password'           => 'NewPassword',
            'password_confirmation' => 'NewPassword'
        ];
        
        $response = $this->actingAs($user)->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'User Password Updated Successfully']);
    }
    
    //  Test viewing the edit form as Installer
    public function testViewFormInstaller()
    {
        $user = $this->getInstaller();
        
        $response = $this->actingAs($user)->get(route('admin.changePassword', $this->user->user_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.changePassword');
    }
    
    //  Test submitting the edit form as Installer
    public function testSubmitFormInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'password'           => 'NewPassword',
            'password_confirmation' => 'NewPassword'
        ];
        
        $response = $this->actingAs($user)->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'User Password Updated Successfully']);
    }
    
    //  Test submitting the form with mismatching passwords
    public function testSubmitFormDuplicateUsername()
    {
        $duplicate = $this->getTech();
        $user      = $this->getInstaller();
        $data      = [
            'password'           => 'NewPassword',
            'password_confirmed' => 'NewNotTheSame'
        ];
        
        $response = $this->actingAs($user)->post(route('admin.changePassword', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }
}
