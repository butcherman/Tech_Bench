<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserVerificationCode;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $user = User::factory()->create();

        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '123456',
            'remember_device' => false,
        ];

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login.email'), $data);

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
    }

    public function test_invoke_bad_code(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $user = User::factory()->create();

        UserVerificationCode::createQuietly([
            'user_id' => $user->user_id,
            'code' => '123456',
        ]);

        $data = [
            'code' => '654321',
            'remember_device' => false,
        ];

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login.email'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('two-factor.login'))
            ->assertSessionHasErrors('code');
    }
}
