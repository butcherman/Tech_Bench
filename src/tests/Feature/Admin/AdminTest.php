<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.index'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.index'));
        $response->assertSuccessful();
    }
}
