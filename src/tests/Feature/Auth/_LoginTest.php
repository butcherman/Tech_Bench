<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\DeviceToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class _LoginTest extends TestCase
{
    // Verify the a valid user can log in with username
    public function test_valid_login_with_username(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password' => bcrypt($password = 'randomPassword'),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        Event::assertDispatched(Login::class);
    }

    // Verify the a valid user can log in with email address
    public function test_valid_login_with_email(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password' => bcrypt($password = 'randomPassword'),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        Event::assertDispatched(Login::class);
    }

    // Verify user cannot login with incorrect password
    public function test_incorrect_login(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['username' => __('auth.failed')]);
        $this->assertGuest();

        Event::assertDispatched(Failed::class);
    }

    // Verify a user that has been deactivated is not able to login
    public function test_login_as_disabled_user(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password' => bcrypt($password = 'randomPassword'),
            'deleted_at' => Carbon::yesterday(),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['username' => __('auth.failed')]);
        $this->assertGuest();

        Event::assertDispatched(Failed::class);
    }

    // Verify that the user is redirected if already logged in
    public function test_valid_login_redirect(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    // Verify that a user is locked out if they try more than five login attempts
    public function test_login_lockout(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        //  Attempt five failed attempts
        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);
        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        //  Sixth attempt should fail
        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('throttle');
        $this->assertGuest();

        Event::assertDispatched(Lockout::class);

        //  After more than 10 minutes, user should be able to try again
        Carbon::setTestNow(Carbon::now()->addMinutes(15));
        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
        $this->assertGuest();

        Event::assertDispatched(Failed::class);
    }

    // Make sure that the user is redirected to the Change Password page if their password has expired
    public function test_password_expired_redirect(): void
    {
        config(['auth.twoFa.required' => false]);

        /** @var User $user */
        $user = User::factory()
            ->createQuietly(['password_expires' => Carbon::yesterday()]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('user.change-password.show'))
            ->assertSessionHasErrors(['password']);
    }

    // Make sure that the user is redirected to the 2fa page if enabled
    public function test_redirect_two_fa(): void
    {
        Mail::fake();

        config(['auth.twoFa.required' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('2fa.show'));

        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);

        Mail::assertQueued(VerificationCodeMail::class);
    }

    // Make sure that if the password is expired, user is redirected to 2fa page first (if enabled)
    public function test_redirect_two_fa_with_password_expired(): void
    {
        Mail::fake();

        config(['auth.twoFa.required' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly([
            'password_expires' => Carbon::yesterday(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('2fa.show'));

        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);

        Mail::assertQueued(VerificationCodeMail::class);
    }

    // Make sure that if the user has a remember device token, it will bypass the 2fa page
    public function test_redirect_with_valid_device_token(): void
    {
        Mail::fake();

        config(['auth.twoFa.required' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $deviceToken = DeviceToken::factory()
            ->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->withCookie('remember_device', $deviceToken->token)
            ->get(route('dashboard'));
        $response->assertSuccessful();

        Mail::assertNotQueued(VerificationCodeMail::class);
    }
}
