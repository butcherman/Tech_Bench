<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('dashboard'));

        $response->assertSuccessful();
        $response->assertViewIs('dashboard');
        $this->assertAuthenticated();
    }

    public function test_index_guest()
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
