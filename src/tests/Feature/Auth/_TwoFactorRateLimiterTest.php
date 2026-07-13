<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class _TwoFactorRateLimiterTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Verify Rate Limiter works for Two Factor Provider
    |---------------------------------------------------------------------------
    */
    public function test_invoke_rate_limiter_test(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        $data = [
            'code' => '654321',
            'remember_device' => false,
        ];

        $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);
        $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);
        $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);
        $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);
        $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->post(route('two-factor.login'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors('throttle');
    }
}
