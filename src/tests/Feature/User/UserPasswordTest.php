<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class UserPasswordTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('user.change-password.show'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('user.change-password.show'));

        $response->assertSuccessful();
    }
}
