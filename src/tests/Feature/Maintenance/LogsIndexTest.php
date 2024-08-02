<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Tests\TestCase;

class LogsIndexTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('maint.logs.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('maint.logs.index'));
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.index'));
        $response->assertSuccessful();
    }

    public function test_invoke_guest_with_channel()
    {
        $response = $this->get(route('maint.logs.show', ['User']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission_with_channel()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('maint.logs.show', ['User']));
        $response->assertStatus(403);
    }

    public function test_invoke_with_invalid_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.show', ['YourMom']));
        $response->assertStatus(404);
    }

    public function test_invoke_with_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.show', ['daily']));
        $response->assertSuccessful();
    }
}
