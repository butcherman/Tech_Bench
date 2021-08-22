<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

use App\Models\User;

class LogoutTest extends TestCase
{
    //  Verify that the user can log out
    public function test_logout()
    {
        $response = $this->actingAs(User::factory()->create())->post(route('logout'));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
