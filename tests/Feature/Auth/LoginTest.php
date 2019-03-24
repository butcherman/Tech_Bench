<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase; 
    
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
    public function testValidLogin()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'randomPassword')
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));   
//        $response->assertAuthenticatedAs($user);
    }
    
    //  Verify user cannot login with incorrect password
    public function testIncorrectLogin()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'randomPassword')
        ]);
        
        $response = $this->from(route('login'))->json('POST', route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse'
        ]);
        $response->assertStatus(422);
        $this->assertGuest();
    }
    
    //  Verify a user that has been deactivated is not able to login
    public function testDisabledUserLogin()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'randomPassword'),
            'active'   => 0
        ]);
        
        $response = $this->from(route('login'))->json('POST', route('login'), [
            'username' => $user->username,
            'password' => $password
        ]);
        $response->assertStatus(422);
        $this->assertGuest();
    }
    
    //  Verify that the user is redirected if trying to veiw the login form again
    public function testPostLoginRedirect()
    {
        $user = factory(User::class)->make();
        
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
    
    //  Test the "Remember Me" token
    public function testRememberMe()
    {   
        $user = factory(User::class)->create([
            'user_id'  => random_int(1, 100),
            'password' => bcrypt($password = 'myPassword'),
        ]);
        
        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password,
            'remember' => 'on',
        ]);
        
        $response->assertRedirect(route('dashboard'));
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->user_id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
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
