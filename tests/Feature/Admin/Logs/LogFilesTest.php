<?php

namespace Tests\Feature\Admin\Logs;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogFilesTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.logs.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
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
        $response = $this->get(route('admin.logs.index', ['User']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission_with_channel()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.index', ['User']));
        $response->assertStatus(403);
    }

    public function test_invoke_with_channel()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.index', ['User']));
        $response->assertSuccessful();
    }
}
