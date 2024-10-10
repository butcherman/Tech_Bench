<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        // Enable Features to have them displayed on login page
        config(['tech-tips.allow_public' => true]);

        $response = $this->get(route('home'));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_invoke_as_logged_in()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('home'));
        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }
}
