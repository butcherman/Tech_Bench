<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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