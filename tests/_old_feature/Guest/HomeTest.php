<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

use App\Models\User;

class HomeTest extends TestCase
{
    //  Test home - / page
    public function test_index_route()
    {
        $response = $this->get(route('home'));
        $response->assertSuccessful();
    }

    //  Test login - /login page
    public function test_login_route()
    {
        $response = $this->get(route('login.index'));
        $response->assertSuccessful();
    }

    //  Verify that the user is redirected if already logged in
    public function test_valid_login_redirect()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
}
