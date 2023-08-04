<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Tests\TestCase;

class CheckForInitTest extends TestCase
{
    /**
     * Test the Check for Init Middleware
     */
    public function test_as_guest()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_as_regular_user()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->actingAs(User::factory()->create())->get(route('dashboard'));
        $response->assertStatus(403);
    }

    public function test_as_installer()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->actingAs(User::find(1))->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('init.welcome'));
    }
}
