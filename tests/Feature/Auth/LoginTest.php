<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{
    //  Verify login page shows
    public function test_view_login_form()
    {
        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    //  Verify the No Script page shows
    public function test_no_script_page()
    {
        $response = $this->get(route('noscript'));

        $response->assertSuccessful();
        $response->assertViewIs('err.noscript');
    }

    //  Verify the guest cannot visit default Laravel Register page
    public function test_register_page()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
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
    }

    //  Verify user cannot login with incorrect password
    public function test_incorrect_login()
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
    public function test_login_as_disabled_user()
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
    public function test_valid_login_redirect()
    {
        $user = factory(User::class)->make();

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

    //  Test user is able to logout
    public function test_logout()
    {
        $user = User::find(2);

        $response = $this->get(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
