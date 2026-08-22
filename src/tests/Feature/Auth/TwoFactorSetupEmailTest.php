<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\User;
use App\Models\UserVerificationCode;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TwoFactorSetupEmailTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Setup Method
    |---------------------------------------------------------------------------
    */
    public function test_setup_guest(): void
    {
        config(['auth.twoFa.required' => true]);

        $response = $this->get(route('two-factor.setup.email'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_setup(): void
    {
        config(['auth.twoFa.required' => true]);

        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('two-factor.setup.email'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorSetupEmail')
                    ->has('allow-remember')
            );

        Mail::assertQueued(VerificationCodeMail::class);
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

        $response = $this->actingAs($user)
            ->post(route('two-factor.setup.email.verify'), $data);

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

        $response = $this->actingAs($user)
            ->post(route('two-factor.setup.email.verify'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrorsIn('mfa', ['code']);
    }
}
