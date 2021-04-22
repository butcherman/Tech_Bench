<?php

namespace Tests\Feature\Home;

use Tests\TestCase;

use App\Models\User;

class AboutTest extends TestCase
{
    public function test_about_page_as_guest()
    {
        $response = $this->get(route('about'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_about_page()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('about'));

        $response->assertSuccessful();
    }
}
