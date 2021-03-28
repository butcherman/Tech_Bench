<?php

namespace Tests\Feature\Home;

use Carbon\Carbon;
use Tests\TestCase;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthTest extends TestCase
{
    //  Test home - / page
    public function test_index_route()
    {
        $response = $this->get(route('home'));
        $response->assertSuccessful();
    }

    //  Test login - /login page
    public function test_login_route()
    {
        $response = $this->get(route('login'));
        $response->assertSuccessful();
    }

    //  Verify the a valid user can log in
    public function test_valid_login()
    {
        $user = User::factory()->create([
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
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
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

        $response = $this->post(route('login'), [
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

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //  TODO - Test Remember Me Token

    //  Test the "Remember Me" token
    // public function test_remember_me()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->post(route('login'), [
    //         'username' => $user->username,
    //         'password' => 'password',
    //         'remember' => true,
    //     ]);

    //     $response->assertRedirect(route('dashboard'));
    //     $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
    //         $user->user_id,
    //         $user->getRememberToken(),
    //         $user->password,
    //     ]));
    //     $this->assertAuthenticatedAs($user);
    // }

        //  TODO  - Finish these Routes

    //  Test logging in when the user needs to reset their password
    // public function test_after_login_with_expired_password()
    // {
    //     $user = User::factory()->create([
    //         'password_expires' => Carbon::yesterday(),
    //     ]);

    //     $response = $this->actingAs($user)->get(route('dashboard'));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('change_password'));
    //     $response->assertSessionHas('change_password');
    //     $this->assertAuthenticatedAs($user);
    // }

    //  Test user is able to logout
    // public function test_logout()
    // {
    //     $user = $this->getTech();

    //     $response = $this->actingAs($user)->post(route('logout', ['logout' => true]));
    //     $response->assertStatus(302);
    //     $response->assertRedirect('/');
    //     $this->assertGuest();
    // }

    //  Test visiting login page when already logged in
    // public function test_login_already_logged_in()
    // {
    //     $response = $this->actingAs($this->getTech())->get(route('home'));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('dashboard'));
    // }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //  Verify user can view the email reset password form
    public function test_reset_password_form()
    {
        $response = $this->get(route('forgot-password'));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    //  Verify user cannot view the reset email form when logged in
    public function test_reset_password_form_while_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('forgot-password'));
        $response->assertRedirect(route('dashboard'));
        $response->assertStatus(302);
    }

    //  Verify that the "Forgot Password" form creates an email with link
    public function test_submit_reset_password_form()
    {
        Notification::fake();
        $user = User::factory()->create();

        $response = $this->post(route('forgot-password'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message');
        $this->assertNotNull($token = DB::table('password_resets')->first());
    }

    //  Verify that the "Forgot Password" form will not email if invalid email is entered
    public function test_submit_reset_password_form_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('forgot-password'), [
            'email' => 'randomEmail@em.com',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(User::factory()->make(['email' => 'randomEmail@em.com']), ResetPassword::class);
    }

    //  Test user submitting form without putting in an email address
    public function test_submit_reset_password_no_email()
    {
        $response = $this->post(route('forgot-password'), []);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('email');
    }

    //  Verify the user can view the reset form
    public function test_view_password_reset_form()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', $token));

        $response->assertSuccessful();
    }

    //  Verify the user cannot view the reset form if already logged in
    public function test_view_password_form_while_logged_in()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->actingAs($user)->get(route('password.reset', $token));

        $response->assertRedirect(route('dashboard'));
    }

    //  Verify the user can reset their password if the token is valid
    public function test_submit_reset_password_form_valid()
    {
        Event::fake();
        $user = User::factory()->create();

        $response = $this->post(route('password.reset'), [
            'token'                 => Password::broker()->createToken($user),
            'email'                 => $user->email,
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
    }

    //  Verify user cannot reset a password with an Invalid Reset token
    public function test_submit_reset_password_form_invalid_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->from(route('password.reset', 'InvalidToken'))->post(route('password.reset'), [
            'token' => 'InvalidToken',
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect(route('password.reset', 'InvalidToken'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    //  Verify user cannot reset to blank password
    public function test_submit_reset_password_form_blank_new_pass()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $response = $this->from(route('password.reset', $token))->post(route('password.reset'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    //  Verify user cannot reset password without providing valid email address
    public function test_submit_reset_password_form_no_email()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $response = $this->from(route('password.reset', $token))->post(route('password.reset'), [
            'token'                 => $token,
            'email'                 => '',
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
