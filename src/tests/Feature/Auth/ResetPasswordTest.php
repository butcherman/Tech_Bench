<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     * Verify user can view the email reset password form
     */
    public function test_reset_password_form()
    {
        $response = $this->get(route('password.forgot'));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    /**
     * Verify user cannot view the reset email form when logged in
     */
    public function test_reset_password_form_while_logged_in()
    {
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('password.forgot'));

        $response->assertRedirect(route('dashboard'))->assertStatus(302);
    }

    /**
     * Verify that the "Forgot Password" form creates an email with link
     */
    public function test_submit_reset_password_form()
    {
        Notification::fake();

        $user = User::factory()->createQuietly();

        $response = $this->post(route('password.forgot'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('status', __('passwords.sent'));
        $this->assertDatabaseHas('password_resets', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * Verify that the "Forgot Password" form will not email if invalid email is entered
     */
    public function test_submit_reset_password_form_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('password.forgot'), [
            'email' => 'randomEmail@em.com',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('email');

        Notification::assertNotSentTo(
            User::factory()->make(['email' => 'randomEmail@em.com']),
            ResetPassword::class
        );
    }

    /**
     * Test user submitting form without putting in an email address
     */
    public function test_submit_reset_password_no_email()
    {
        $response = $this->post(route('password.forgot'), []);

        $response->assertRedirect(route('home'))->assertSessionHasErrors('email');
    }

    /**
     * Verify the user can view the reset form
     */
    public function test_view_password_reset_form()
    {
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertSuccessful();
    }

    /**
     * Verify the user cannot view the reset form if no token or email address is present
     */
    public function test_view_password_reset_form_no_token()
    {
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', $token));

        $response->assertStatus(404);
    }

    /**
     * Verify the user cannot view the reset form if already logged in
     */
    public function test_view_password_form_while_logged_in()
    {
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->actingAs($user)->get(route('password.reset', $token));

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Verify the user can reset their password if the token is valid
     */
    public function test_submit_reset_password_form_valid()
    {
        Notification::fake();

        $user = User::factory()->createQuietly();

        $response = $this->post(route('password.reset'), [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
            'password' => 'New-awesome-password1!',
            'password_confirmation' => 'New-awesome-password1!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(
            Hash::check('New-awesome-password1!', $user->fresh()->password)
        );

        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    /**
     * Verify user cannot reset a password with an Invalid Reset token
     */
    public function test_submit_reset_password_form_invalid_token()
    {
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->from(route('password.reset', 'InvalidToken'))
            ->post(route('password.reset'), [
                'token' => 'InvalidToken',
                'email' => $user->email,
                'password' => 'New-awesome-password1!',
                'password_confirmation' => 'New-awesome-password1!',
            ]);

        $response->assertRedirect(route('password.reset', 'InvalidToken'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * Verify user cannot reset to blank password
     */
    public function test_submit_reset_password_form_blank_new_pass()
    {
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $response = $this->from(route('password.reset', $token))
            ->post(route('password.reset'), [
                'token' => $token,
                'email' => $user->email,
                'password' => '',
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

    /**
     * Verify user cannot reset password without providing valid email address
     */
    public function test_submit_reset_password_form_no_email()
    {
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $response = $this->from(route('password.reset', $token))
            ->post(route('password.reset'), [
                'token' => $token,
                'email' => '',
                'password' => 'new-awesome-password',
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
