<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('password.reset', ['email' => 'em@some.com', 'token' => 'blahblahblah']));
        
        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_invoke_no_token()
    {
        $response = $this->get(route('password.reset'));

        $response->assertStatus(404);
        $this->assertGuest();
    }

    public function test_invoke_as_logged_in()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('password.reset'));

        $response->assertStatus(302)->assertRedirect(route('dashboard'));
    }
}
