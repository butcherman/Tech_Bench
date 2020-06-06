<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{
    //  Test home - / page
    public function test_index_route()
    {
        $response = $this->get(route('index'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    //  Test login - /login page
    public function test_login_route()
    {
        $response = $this->get(route('login'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    //  Verify the a valid user can log in
    public function test_valid_login()
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
        $this->assertAuthenticatedAs($user);
    }

    //  Verify user cannot login with incorrect password
    public function test_incorrect_login()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('index'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    //  Verify a user that has been deactivated is not able to login
    public function test_login_as_disabled_user()
    {
        $user = factory(User::class)->create([
            'password'   => bcrypt($password = 'randomPassword'),
            'deleted_at' => Carbon::yesterday(),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('index'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    //  Verify that the user is redirected if already logged in
    public function test_valid_login_redirect()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    //  Test the "Remember Me" token
    public function test_remember_me()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'password',
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

    //  Test logging in when the user needs to reset their password
    public function test_after_login_with_expired_password()
    {
        $user = factory(User::class)->create([
            'password_expires' => Carbon::yesterday(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('change_password'));
        $response->assertSessionHas('change_password');
        $this->assertAuthenticatedAs($user);
    }

    //  Test user is able to logout
    public function test_logout()
    {
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('logout', ['logout' => true]));
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    //  Test visiting login page when already logged in
    public function test_login_already_logged_in()
    {
        $response = $this->actingAs($this->getTech())->get(route('index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    // //  Test Internet Explorer redirect
    // public function test_ie_redirect()
    // {
    //     // $response = $this->withHeaders(['user-agent' => ['Trident/7.0; rv:11.0']])->get(route('index'));




    //     $response = $this->withHeaders(['user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko'])->get(route('index'));




    //     dd($response->getContent());
    // }
}
