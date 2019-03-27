<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditUserTest extends TestCase
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
        $response = $this->get(route('admin.user.edit', $this->user->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test submitting the edit form as guest
    public function testSubmitFormGuest()
    {
        $data = [
            'username'   => 'newUsername',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail@em.em',
            'role'       => 4
        ];
        
        $response = $this->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test viewing the edit form as tech
    public function testViewFormTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Test submitting the edit form as tech
    public function testSubmitFormTech()
    {
        $user = $this->getTech();
        $data = [
            'username'   => 'newUsername',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail@em.em',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Test viewing the edit form as Report user
    public function testViewFormReport()
    {
        $user = $this->getReport();
        
        $response = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Test submitting the edit form as Report User
    public function testSubmitFormReport()
    {
        $user = $this->getReport();
        $data = [
            'username'   => 'newUsername',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail@em.em',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Test viewing the edit form as Admin
    public function testViewFormAdmin()
    {
        $user = $this->getAdmin();
        
        $response = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.editUser');
    }
    
    //  Test submitting the edit form as Admin
    public function testSubmitFormAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'username'   => 'newUsername55',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail55@em.em',
            'role'       => 3
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'User Updated Successfully']);
    }
    
    //  Test viewing the edit form as Installer
    public function testViewFormInstaller()
    {
        $user = $this->getInstaller();
        
        $response = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.editUser');
    }
    
    //  Test submitting the edit form as Installer
    public function testSubmitFormInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'username'   => 'newUsername',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail@em.em',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'User Updated Successfully']);
    }
    
    //  Test submitting the form with a duplicate username
    public function testSubmitFormDuplicateUsername()
    {
        $duplicate = $this->getTech();
        $user      = $this->getInstaller();
        $data      = [
            'username'   => $duplicate->username,
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => 'newEmail33@em.em',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }
    
    //  Test submitting the form with a duplicate email
    public function testSubmitFormDuplicateEmail()
    {
        $duplicate = $this->getTech();
        $user      = $this->getInstaller();
        $data      = [
            'username'   => 'NewUsernameForMe',
            'first_name' => 'FirstName',
            'last_name'  => 'lastName',
            'email'      => $duplicate->email,
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->put(route('admin.user.update', $this->user->user_id), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }
}
