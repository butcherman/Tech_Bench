<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReactivateUserTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->get(route('admin.reactivate-user', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.reactivate-user', $user->username));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.reactivate-user', $user->username));
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', $user->only(['user_id', 'username', 'first_name', 'last_name']));
    }
}
