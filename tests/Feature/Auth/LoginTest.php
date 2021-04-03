<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    //  Verify the a valid user can log in
    public function test_valid_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'randomPassword')
        ]);

        $response = $this->post(route('login.submit'), [
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
        $user = User::factory()->create();

        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => 'somethingElse'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    //  Verify a user that has been deactivated is not able to login
    public function test_login_as_disabled_user()
    {
        $user = User::factory()->create([
            'password'   => bcrypt($password = 'randomPassword'),
            'deleted_at' => Carbon::yesterday(),
        ]);

        $response = $this->post(route('login.submit'), [
            'username' => $user->username,
            'password' => $password
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    //  Verify that the user is redirected if already logged in
    public function test_valid_login_redirect()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    //  TODO - Test Remember Me Token
}
