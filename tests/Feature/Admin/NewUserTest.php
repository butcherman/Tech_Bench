<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Mail\InitializeUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewUserTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('admin.user.create'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a Tech user cannot visit the page
    public function testTech()
    {
        $user = $this->getTech();
            
        $response = $this->actingAs($user)->get(route('admin.user.create'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a Report user can visit the page
    public function testReport()
    {
        $user = $this->getReport();
            
        $response = $this->actingAs($user)->get(route('admin.user.create'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an Admin user can visit the page
    public function testAdmin()
    {
        $user = $this->getAdmin();
            
        $response = $this->actingAs($user)->get(route('admin.user.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.newUser');
    }
    
    //  Verify that an Installer user can visit the page
    public function testInstaller()
    {
        $user = $this->getInstaller();
            
        $response = $this->actingAs($user)->get(route('admin.user.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('admin.newUser');
    }
    
    //  Verify that a guest cannot submit a new user
    public function testNewUserGuest()
    {
        $data = [
            'username'   => 'myUsername',
            'first_name' => 'Billy',
            'last_name'  => 'Bob',
            'email'      => 'unique@email.trash',
            'role'       => 4
        ];
        
        $response = $this->post(route('admin.user.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a registered Tech cannot submit a new user
    public function testNewUserTech()
    {
        $user = $this->getTech();
        
        $data = [
            'username'   => 'myUsername',
            'first_name' => 'Billy',
            'last_name'  => 'Bob',
            'email'      => 'unique@email.trash',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a registered Report user cannot submit a new user
    public function testNewUserReport()
    {
        $user = $this->getReport();
        
        $data = [
            'username'   => 'myUsername',
            'first_name' => 'Billy',
            'last_name'  => 'Bob',
            'email'      => 'unique@email.trash',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a registered Admin can submit a new user
    public function testNewUserAdmin()
    {
        Mail::fake();
        $user = $this->getAdmin();
        
        $data = [
            'username'   => 'myUsername',
            'first_name' => 'Billy',
            'last_name'  => 'Bob',
            'email'      => 'unique@email.trash',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'New User Created']);
        Mail::assertQueued(InitializeUser::class, function($mail) use ($data)
        {
            return $mail->hasTo($data['email']);
        });
    }
    
    //  Verify that a registered Installer can submit a new user
    public function testNewUserInstaller()
    {
        Mail::fake();
        $user = $this->getAdmin();
        
        $data = [
            'username'   => 'newUsername',
            'first_name' => 'Random',
            'last_name'  => 'Name',
            'email'      => 'another@email.trash',
            'role'       => 4
        ];
        
        $response = $this->actingAs($user)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success' => 'New User Created']);
        Mail::assertQueued(InitializeUser::class, function($mail) use ($data)
        {
            return $mail->hasTo($data['email']);
        });
    }
    
    //  Test creating a user with a username that already exists
    public function testDuplicateUsername()
    {
        $admin = $this->getAdmin();
        $user  = $this->getTech();
        
        $data = [
            'username'   => $user->username,
            'first_name' => 'Another',
            'last_name'  => 'Name',
            'email'      => 'anotherEmail@email.trash',
            'role'       => 4
        ];
        
        $response = $this->actingAs($admin)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }
    
    //  Test creating a user with a username that already exists
    public function testDuplicateEmail()
    {
        $admin = $this->getAdmin();
        $user  = $this->getTech();
        
        $data = [
            'username'   => 'randomUsername',
            'first_name' => 'Another',
            'last_name'  => 'Name',
            'email'      => $user->email,
            'role'       => 4
        ];
        
        $response = $this->actingAs($admin)->post(route('admin.user.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }
}
