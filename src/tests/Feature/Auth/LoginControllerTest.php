<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest(): void
    {
        // Enable Features to have them displayed on login page
        config(['tech-tips.allow_public' => true]);

        $response = $this->get(route('home'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Auth/Login')
                ->has('welcome-message')
                ->has('home-links')
                ->has('public-link')
                ->has('allow-oath')
            );
        $this->assertGuest();
    }

    public function test_invoke_as_logged_in(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('home'));

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }
}
