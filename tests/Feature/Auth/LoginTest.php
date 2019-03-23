<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    //  Verify login page shows
    public function testViewLoginForm()
    {
        $response = $this->get('/');
        
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }
    
    //  Verify the gues cannot visit default Laravel Register page
    public function testRegisterPage()
    {
        $response = $this->get(route('register'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    
    //  Verify the user can login with admin rights
    public function testLoginAsAdmin()
    {
        $user = [
            'username' => 'admin',
            'password' => 'password'
        ];
        
        $response = $this->json('POST', route('login'), $user);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));   
        
    }
    
    //  Verify user cannot login with incorrect password
    public function testIncorrectLogin()
    {
        $user = [
            'username' => 'admin',
            'password' => 'something_random'
        ];
        
        $response = $this->from(route('login'))->json('POST', route('login'), $user);
        $response->assertStatus(422);
        $this->assertGuest();
    }
    
    //  Verify that the user is redirected if trying to veiw the login form again
    public function testPostLoginRedirect()
    {
        $this->actAsTech();
        
        $response = $this->get('/');
        
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
    
    //  Test the "Remember Me" token
    public function testRememberMe()
    {
        $user = [
            'username' => 'admin',
            'password' => 'password',
            'remember' => 'on'
        ];
        
        $adminUser = User::find(1);
        $response = $this->post(route('login'), $user);
        
        $response->assertRedirect(route('dashboard'));
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
                $adminUser->user_id,
                $adminUser->getRememberToken(),
                $adminUser->password,
            ]));
        $this->assertAuthenticatedAs($adminUser);
    }
    
    //  Test user is able to logout
    public function testLogout()
    {
        $user = User::find(2);
        
        $response = $this->get(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
