<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TwoFactorSetupAuthenticatorTest extends TestCase
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

        $response = $this->get(route('two-factor.setup.authenticator'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('two-factor.setup.authenticator'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAppSetup')
            );
    }
}
