<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserPasswordTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('user.change-password.show'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('user.change-password.show'));

        $response->assertSuccessful();
    }

    /**
     * Test Submitting the Change Passwor form (processed by Fortify)
     */
    public function test_submit_password_change_guest()
    {
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'newS3cureP@ss',
            'password_confirmation' => $pass,
        ];

        $response = $this->put(route('user-password.update', $data));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test Submitting the Change Passwor form (processed by Fortify)
     */
    public function test_submit_password_change()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'newS3cureP@ss',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update', $data));
        $response->assertStatus(302);

        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    /**
     * Test Password Complexity rules
     */
    public function test_change_password_no_lowercase_enabled()
    {
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3WSECUREPA33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertInvalid();
    }

    public function test_change_password_no_lowercase_disabled()
    {
        Notification::fake();

        config(['auth.passwords.settings.contains_lowercase' => false]);
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3WSECUREPA33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertValid();

        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    public function test_change_password_no_uppercase_enabled()
    {
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'n3wsecurepa33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertInvalid();
    }

    public function test_change_password_no_uppercase_disabled()
    {
        Notification::fake();

        config(['auth.passwords.settings.contains_uppercase' => false]);
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'n3wsecurepa33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertValid();

        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    public function test_change_password_no_number_enabled()
    {
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'NewSecurePass!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertInvalid();
    }

    public function test_change_password_no_number_disabled()
    {
        Notification::fake();

        config(['auth.passwords.settings.contains_number' => false]);
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'NewSecurePass!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertValid();
    }

    public function test_change_password_no_special_enabled()
    {
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertInvalid();
    }

    public function test_change_password_no_special_disabled()
    {
        Notification::fake();

        config(['auth.passwords.settings.contains_special' => false]);
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);
        $response->assertStatus(302);
        $response->assertValid();
    }
}
