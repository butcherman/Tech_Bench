<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class TechTipBookmarkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();
        $data = [
            'value' => true,
        ];

        $response = $this->post(route('tech-tips.bookmark', $tip->slug), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_add()
    {
        $user = User::factory()->create();
        $tip = TechTip::factory()->create();
        $data = [
            'value' => true,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $tip->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $tip->tip_id,
        ]);
    }

    public function test_invoke_add_duplicate()
    {
        $user = User::factory()->create();
        $tip = TechTip::factory()->create();
        $data = [
            'value' => true,
        ];

        $tip->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $tip->slug), $data);
        $response->assertStatus(500);
    }

    public function test_invoke_remove()
    {
        $user = User::factory()->create();
        $tip = TechTip::factory()->create();
        $data = [
            'value' => false,
        ];

        $tip->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $tip->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $tip->tip_id,
        ]);
    }

    public function test_invoke_remove_duplicate()
    {
        $user = User::factory()->create();
        $tip = TechTip::factory()->create();
        $data = [
            'value' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $tip->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $tip->tip_id,
        ]);
    }
}
