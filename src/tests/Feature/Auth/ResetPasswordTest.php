<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/ResetPassword')
                    ->has('email')
                    ->has('token')
                    ->has('rules')
            );
        $this->assertGuest();
    }

    public function test_invoke_as_logged_in(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('password.reset'));

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }
}
