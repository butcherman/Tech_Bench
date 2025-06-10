<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class _ForgotPasswordTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test the Reset Password features
    |---------------------------------------------------------------------------
    */

    /**
     * Verify user can view the email reset password form
     */
    public function test_reset_password_form(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/ForgotPassword')
            );
        $this->assertGuest();
    }

    /**
     * Verify user cannot view the reset email form when logged in
     */
    public function test_reset_password_form_while_logged_in(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('password.request'));

        $response->assertRedirect(route('dashboard'))
            ->assertStatus(302);
    }

    /**
     * Verify that the "Forgot Password" form creates an email with link
     */
    public function test_submit_reset_password_form(): void
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('status', __('passwords.sent'));

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * Verify that the "Forgot Password" form will not email if invalid email is entered
     */
    public function test_submit_reset_password_form_invalid_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
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
    public function test_submit_reset_password_no_email(): void
    {
        $response = $this->post(route('password.email'), []);

        $response->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /**
     * Verify the user can view the reset form
     */
    public function test_view_password_reset_form(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertSuccessful();
    }

    /**
     * Verify the user cannot view the reset form if already logged in
     */
    public function test_view_password_form_while_logged_in(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->actingAs($user)->get(route('password.reset', $token));

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Verify the user can reset their password if the token is valid
     */
    public function test_submit_reset_password_form_valid(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->post(route('password.update'), [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
            'password' => 'New-awesome-password1!',
            'password_confirmation' => 'New-awesome-password1!',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(
            Hash::check('New-awesome-password1!', $user->fresh()->password)
        );

        Event::assertDispatched(PasswordReset::class);
    }

    /**
     * Verify user cannot reset a password with an Invalid Reset token
     */
    public function test_submit_reset_password_form_invalid_token(): void
    {
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'InvalidToken',
            'email' => $user->email,
            'password' => 'New-awesome-password1!',
            'password_confirmation' => 'New-awesome-password1!',
        ]);

        $response->assertStatus(302);

        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * Verify user cannot reset to blank password
     */
    public function test_submit_reset_password_form_blank_new_pass(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * Verify user cannot reset password without providing valid email address
     */
    public function test_submit_reset_password_form_no_email(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => '',
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
