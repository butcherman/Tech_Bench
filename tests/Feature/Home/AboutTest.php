<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AboutTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_about_guest()
    {
        $response = $this->get(route('about'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_about()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('about'));
        $response->assertSuccessful();
    }
}
