<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase; 
    
    //  Verify that the list of users cannot be pulled by guest
    public function testGetUsersGuest()
    {
        $response = $this->get(route('admin.user.show', 'active'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that the list of users cannot be pulled by Tech User
    public function testGetUsersTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('admin.user.show', 'active'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that the list of users cannot be pulled by Report User
    public function testGetUsersReport()
    {
        $user = $this->getReport();
        
        $response = $this->actingAs($user)->get(route('admin.user.show', 'active'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that the list of users can be pulled by Admin User
    public function testGetUsersAdmin()
    {
        $user = $this->getAdmin();
        
        $response = $this->actingAs($user)->get(route('admin.user.show', 'active'));
        $response->assertSuccessful();
        $response->assertJsonStructure([['user_id', 'user', 'email', 'last']]);
    }
    
    //  Verify that the list of users can be pulled by Installer User
    public function testGetUsersInstaller()
    {
        $user = $this->getInstaller();
        
        $response = $this->actingAs($user)->get(route('admin.user.show', 'active'));
        
        $response->assertSuccessful();
        $response->assertJsonStructure([['user_id', 'user', 'email', 'last']]);
    }
    
    //  Verify that a guest cannot disable a user
    public function testDisableUserGuest()
    {
        $user = $this->getTech();
        
        $response = $this->delete(route('admin.user.destroy', $user->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot disable a user
    public function testDisableUserTech()
    {
        $acting = $this->getTech();
        $user   = $this->getTech();
        
        $response = $this->actingAs($acting)->delete(route('admin.user.destroy', $user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report User cannot disable a user
    public function testDisableUserReport()
    {
        $acting = $this->getReport();
        $user   = $this->getTech();
        
        $response = $this->actingAs($acting)->delete(route('admin.user.destroy', $user->user_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin User can disable a user
    public function testDisableUserAdmin()
    {
        $acting = $this->getAdmin();
        $user   = $this->getTech();
        
        $response = $this->actingAs($acting)->delete(route('admin.user.destroy', $user->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas(['success' => 'User Deactivated Successfully']);
    }
    
    //  Verify that an Installer User can disable a user
    public function testDisableUserInstaller()
    {
        $acting = $this->getInstaller();
        $user   = $this->getTech();
        
        $response = $this->actingAs($acting)->delete(route('admin.user.destroy', $user->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas(['success' => 'User Deactivated Successfully']);
    }
}
