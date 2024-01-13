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
        $response = $this->get(route('home'));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_invoke_as_logged_in()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('home'));

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
    }
}
