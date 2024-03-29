<?php

namespace Tests\Feature\Admin\Logo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetLogoTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.get-logo'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.get-logo'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.get-logo'));
        $response->assertSuccessful();
    }
}
