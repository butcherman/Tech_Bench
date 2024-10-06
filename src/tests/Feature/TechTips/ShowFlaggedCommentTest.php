<?php

namespace Tests\Feature\TechTips;

use App\Models\User;
use Tests\TestCase;

class ShowFlaggedCommentTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('tech-tips.comments.show-flagged'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.comments.show-flagged'));
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.show-flagged'));
        $response->assertSuccessful();
    }

    public function test_invoke_feature_disabled()
    {
        config(['tech-tips.allow_comments' => false]);
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.show-flagged'));
        $response->assertForbidden();
    }
}
