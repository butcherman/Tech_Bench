<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_dashboard_guest()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_dashboard()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('dashboard'));
        $response->assertSuccessful();
    }
}
