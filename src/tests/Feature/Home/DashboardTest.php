<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_dashboard_guest(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_dashboard(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page->component('Home/Dashboard'));
    }
}
