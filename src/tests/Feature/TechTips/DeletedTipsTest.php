<?php

namespace Tests\Feature\TechTips;

use App\Models\User;
use Tests\TestCase;

class DeletedTipsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.tech-tips.deleted-tips'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->ActingAs(User::factory()->createQuietly())
            ->get(route('admin.tech-tips.deleted-tips'));
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.tech-tips.deleted-tips'));
        $response->assertSuccessful();
    }
}
