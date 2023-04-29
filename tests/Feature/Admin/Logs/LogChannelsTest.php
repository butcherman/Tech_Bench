<?php

namespace Tests\Feature\Admin\Logs;

use App\Models\User;
use Tests\TestCase;

class LogChannelsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest_with_channel()
    {
        $response = $this->get(route('admin.logs.channels', ['User']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission_with_channel()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.channels', ['User']));
        $response->assertStatus(403);
    }

    public function test_invoke_with_invalid_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.channels', ['YourMom']));
        $response->assertStatus(404);
    }

    public function test_invoke_with_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.channels', ['User']));
        $response->assertSuccessful();
    }
}
