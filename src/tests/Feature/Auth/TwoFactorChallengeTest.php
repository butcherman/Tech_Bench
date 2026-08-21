<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserVerificationCode;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TwoFactorChallengeTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Challenge Method
    |---------------------------------------------------------------------------
    */
    public function test_challenge(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->get(route('two-factor.login'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Auth/TwoFactorChallenge')
                ->has('via')
                ->has('allow-remember')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Verify Method
    |---------------------------------------------------------------------------
    */
    public function test_verify(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User */
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
        ]])->post(route('two-factor.verify'), $data);

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
    }

    public function test_verify_bad_code(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        /** @var User */
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
        ]])->post(route('two-factor.verify'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrorsIn('mfa', ['code']);
    }
}
