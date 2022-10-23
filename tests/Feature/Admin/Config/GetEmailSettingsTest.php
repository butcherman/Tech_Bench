<?php

namespace Tests\Feature\Admin\Config;

use Tests\TestCase;
use App\Models\User;

class GetEmailSettingsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.get-email'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.get-email'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.get-email'));
        $response->assertSuccessful();
    }
}
