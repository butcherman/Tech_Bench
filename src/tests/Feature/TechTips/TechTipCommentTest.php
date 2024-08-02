<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class TechTipCommentTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertForbidden();
    }

    public function test_index()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
}
