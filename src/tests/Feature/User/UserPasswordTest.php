<?php

namespace Tests\Feature\User;

use App\Events\User\UserPasswordChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserPasswordTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('user.change-password.show'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('user.change-password.show'));

        $response->assertSuccessful();
    }

    /**
     * Test Submitting the Change Password form (processed by Fortify)
     */
    public function test_submit_password_change_guest()
    {
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'newS3cureP@ss',
            'password_confirmation' => $pass,
        ];

        $response = $this->put(route('user-password.update', $data));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test Submitting the Change Password form (processed by Fortify)
     */
    public function test_submit_password_change()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'newS3cureP@ss',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update', $data));

        $response->assertStatus(302);

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }

    /**
     * Test Password Complexity rules
     */
    public function test_change_password_no_lowercase_enabled()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3WSECUREPA33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertInvalid();

        Event::assertNotDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_lowercase_disabled()
    {
        config(['auth.passwords.settings.contains_lowercase' => false]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3WSECUREPA33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertValid();

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_uppercase_enabled()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'n3wsecurepa33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertInvalid();

        Event::assertNotDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_uppercase_disabled()
    {
        config(['auth.passwords.settings.contains_uppercase' => false]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'n3wsecurepa33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertValid();

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_number_enabled()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'NewSecurePass!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertInvalid();

        Event::assertNotDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_number_disabled()
    {
        config(['auth.passwords.settings.contains_number' => false]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'NewSecurePass!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertValid();

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_special_enabled()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertInvalid();

        Event::assertNotDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_special_disabled()
    {
        config(['auth.passwords.settings.contains_special' => false]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertValid();

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }

    public function test_change_password_no_compromised_enabled()
    {
        config(['auth.password.settings.disable_compromised' => true]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'password123',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $response->assertStatus(302)
            ->assertInvalid();

        Event::assertNotDispatched(UserPasswordChangedEvent::class);
    }
}
