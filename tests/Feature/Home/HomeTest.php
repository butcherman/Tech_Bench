<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page()
    {
        $response = $this->get(route('home'));

        $response->assertSuccessful();
        $response->assertViewIs('app');
    }

    public function test_home_while_logged_in()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('home'));

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
}
