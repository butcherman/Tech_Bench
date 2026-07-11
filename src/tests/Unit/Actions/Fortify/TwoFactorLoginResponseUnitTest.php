<?php

namespace Tests\Unit\Actions\Fortify;

use App\Models\User;
use App\Models\UserVerificationCode;
use Tests\TestCase;

class TwoFactorLoginResponseUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | toResponse()
    |---------------------------------------------------------------------------
    */
    public function test_to_response_without_remember_device(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => false]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '123456',
            'remember_device' => false,
        ];

        $response = $this->withSession([
            'login' => [
                'id' => $user->user_id,
                'remember' => false,
            ],
        ])->post(route('two-factor.login.email'), $data);

        $response->assertRedirect(route('dashboard'))
            ->assertCookieMissing('remember_device');
    }

    public function test_to_response_without_remember_device_as_json(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => false]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '123456',
            'remember_device' => false,
        ];

        $response = $this->withSession([
            'login' => [
                'id' => $user->user_id,
                'remember' => false,
            ],
        ])->postJson(route('two-factor.login.email'), $data);

        $response->assertStatus(204);
    }

    public function test_to_response_with_remember_device(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => false]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '123456',
            'remember_device' => true,
        ];

        $response = $this->withSession([
            'login' => [
                'id' => $user->user_id,
                'remember' => false,
            ],
        ])->post(route('two-factor.login.email'), $data);

        $response->assertRedirect(route('dashboard'))
            ->assertCookie('remember_device');

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->user_id,
        ]);
    }

    public function test_to_response_with_remember_device_as_json(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => false]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '123456',
            'remember_device' => true,
        ];

        $response = $this->withSession([
            'login' => [
                'id' => $user->user_id,
                'remember' => false,
            ],
        ])->postJson(route('two-factor.login.email'), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->user_id,
        ]);
    }
}
