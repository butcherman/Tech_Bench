<?php

namespace Tests\Unit\Actions\Fortify;

use App\Models\DeviceToken;
use App\Models\User;
use Tests\TestCase;

class RedirectIfTwoFactorAuthenticatableUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_two_fa_disabled(): void
    {
        config(['auth.twoFa.required' => false]);

        $user = User::factory()->create();
        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_handle_with_device_token(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $user = User::factory()->create(['two_factor_via' => 'email']);
        $token = DeviceToken::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->withCookie('remember_device', $token->token)
            ->post(route('login'), $data);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_handle_with_invalid_device_token(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $user = User::factory()->create();
        DeviceToken::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->withCookie('remember_device', 'randomStringNotAUuId')
            ->post(route('login'), $data);

        $response->assertRedirect(route('two-factor.setup.index'));
    }

    public function test_handle_with_authenticator_allowed_but_not_setup(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => false]);

        $user = User::factory()->create();
        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertRedirect(route('two-factor.setup.authenticator'));
    }

    public function test_handle_normal_redirect(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $user = User::factory()->create([
            'two_factor_confirmed_at' => now(),
            'two_factor_via' => 'email',
        ]);
        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertRedirect(route('two-factor.login'));
    }
}
