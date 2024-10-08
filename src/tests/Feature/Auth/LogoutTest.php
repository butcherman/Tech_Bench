<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    //  Verify that the user can log out
    public function test_logout()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('logout'));

        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that the user can log out with a logout message
    public function test_logout_timeout()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('logout'), ['reason' => 'timeout']);

        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
