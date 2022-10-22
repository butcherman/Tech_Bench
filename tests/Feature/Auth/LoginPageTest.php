<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginPageTest extends TestCase
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
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
}
