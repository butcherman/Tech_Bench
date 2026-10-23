<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TwoFactorSetupEmailTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        $response = $this->get(route('two-factor.setup.email'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('two-factor.setup.email'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAuth')
                    ->has('allow-remember')
                    ->has('via')
            );

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_invoke_expect_json(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('two-factor.setup.email'));

        $response->assertSuccessful();

        Mail::assertQueued(VerificationCodeMail::class);
    }
}
