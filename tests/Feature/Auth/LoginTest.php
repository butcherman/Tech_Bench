<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class LoginTest extends TestCase
{
    //  Verify the a valid user can log in
    public function test_valid_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'randomPassword'),
        ]);

        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => $password,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    //  Verify user cannot login with incorrect password
    public function test_incorrect_login()
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        // dd($response->getSession());
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors(['email' => __('auth.failed')]);
        $this->assertGuest();
    }

    //  Verify a user that has been deactivated is not able to login
    public function test_login_as_disabled_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'randomPassword'),
            'deleted_at' => Carbon::yesterday(),
        ]);

        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => $password,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors(['email' => __('auth.failed')]);
        $this->assertGuest();
    }

    //  Verify that the user is redirected if already logged in
    public function test_valid_login_redirect()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    //  Verify that a user is locked out if they try more than five login attempts
    public function test_login_lockout()
    {
        $user = User::factory()->create();

        //  Attempt five failed attempts
        $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        //  Sixth attempt should fail
        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

        //  After more than 10 minutes, user should be able to try again
        Carbon::setTestNow(Carbon::now()->addMinutes(15));
        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors(['email' => __('auth.failed')]);
        $this->assertGuest();
    }

    //  Make sure that the user is redirected to the Change Password page if their password has expired
    public function test_password_expired_redirect()
    {
        $user = User::factory()->create(['password_expires' => Carbon::yesterday()]);


        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(302);
        $response->assertRedirect(route('settings.password.index'));
        $response->assertSessionHasErrors(['password']);
    }
}
