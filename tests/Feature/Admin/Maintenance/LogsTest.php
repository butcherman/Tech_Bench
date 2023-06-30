<?php

namespace Tests\Feature\Admin\Maintenance;

use App\Models\User;
use Tests\TestCase;

class LogsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.logs.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.index'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.index'));
        $response->assertSuccessful();
    }

    public function test_invoke_guest_with_channel()
    {
        $response = $this->get(route('admin.logs.channel', ['User']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission_with_channel()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.channel', ['User']));
        $response->assertStatus(403);
    }

    public function test_invoke_with_invalid_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.channel', ['YourMom']));
        $response->assertSuccessful();
    }

    public function test_invoke_with_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.channel', ['User']));
        $response->assertSuccessful();
    }
}
