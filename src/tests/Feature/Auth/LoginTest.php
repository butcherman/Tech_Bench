<?php

namespace Tests\Feature\Auth;

use App\Models\User;
// use App\Models\UserCode;
// use App\Notifications\User\SendAuthCode;
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

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    //  Verify user cannot login with incorrect password
    public function test_incorrect_login()
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['username' => __('auth.failed')]);
        $this->assertGuest();
    }

    //  Verify a user that has been deactivated is not able to login
    public function test_login_as_disabled_user()
    {
        $user = User::factory()->create([
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
    }

    //  Verify that the user is redirected if already logged in
    public function test_valid_login_redirect()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    //  Verify that a user is locked out if they try more than five login attempts
    public function test_login_lockout()
    {
        $user = User::factory()->create();

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

        //  After more than 10 minutes, user should be able to try again
        Carbon::setTestNow(Carbon::now()->addMinutes(15));
        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'somethingElse',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['username' => __('auth.failed')]);
        $this->assertGuest();
    }

    //  Make sure that the user is redirected to the Change Password page if their password has expired
    public function test_password_expired_redirect()
    {
        $user = User::factory()
            ->create(['password_expires' => Carbon::yesterday()]);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('user.change-password.show'));
        $response->assertSessionHasErrors(['password']);
    }

    //  Make sure that the user is redirected to the 2fa page if enabled
    // public function test_redirect_two_fa()
    // {
    //     Notification::fake();

    //     config(['auth.twoFa.required' => true]);
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->get(route('dashboard'));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('2fa.index'));

    //     $this->assertDatabaseHas('user_codes', ['user_id' => $user->user_id]);

    //     Notification::assertSentTo($user, SendAuthCode::class);
    // }

    //  Make sure that if the password is expired, user is redirected to 2fa page first (if enabled)
    // public function test_redirect_two_fa_with_password_expired()
    // {
    //     Notification::fake();

    //     config(['auth.twoFa.required' => true]);
    //     $user = User::factory()->create(['password_expires' => Carbon::yesterday()]);

    //     $response = $this->actingAs($user)->get(route('dashboard'));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('2fa.index'));

    //     $this->assertDatabaseHas('user_codes', ['user_id' => $user->user_id]);

    //     Notification::assertSentTo($user, SendAuthCode::class);
    // }

    //  Make sure that the SMS channel will file properly
    // public function test_redirect_with_sms_channel()
    // {
    //     Notification::fake();

    //     config(['auth.twoFa.required' => true]);
    //     $user = User::factory()->create();
    //     UserCode::create([
    //         'user_id' => $user->user_id,
    //         'code' => 1234,
    //         'receive_sms' => true,
    //     ]);

    //     $response = $this->actingAs($user)->get(route('dashboard'));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('2fa.index'));

    //     $this->assertDatabaseHas('user_codes', ['user_id' => $user->user_id]);

    //     Notification::assertSentTo($user, SendAuthCode::class);
    // }

    //  Make sure that if the user has a remember device token, it will bypass the 2fa page
    // public function test_redirect_with_valid_device_token()
    // {
    //     Notification::fake();

    //     config(['auth.twoFa.required' => true]);
    //     $user = User::factory()->create();
    //     $deviceToken = $user->generateRememberDeviceToken();

    //     $response = $this->actingAs($user)->withCookie('remember_device', $deviceToken)->get(route('dashboard'));
    //     $response->assertSuccessful();

    //     Notification::assertNotSentTo($user, SendAuthCode::class);
    // }
}