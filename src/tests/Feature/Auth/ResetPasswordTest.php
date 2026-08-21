<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
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
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Auth/ResetPassword')
                ->has('email')
                ->has('token')
                ->has('rules')
            );
    }

    public function test_invoke_as_logged_in(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $token = Password::broker()->createToken($user);

        $response = $this->actingAs($user)->get(route('password.reset', $token));

        $response->assertRedirect(route('dashboard'));
    }
}
