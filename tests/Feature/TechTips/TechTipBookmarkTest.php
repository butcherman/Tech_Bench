<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use App\Models\UserTechTipBookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipBookmarkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->post(route('tips.bookmark'), ['tip_id' => $tip->tip_id, 'state' => true]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_add()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tips.bookmark'), ['tip_id' => $tip->tip_id, 'state' => true]);
        $response->assertSuccessful();
        $response->assertStatus(204);
        $this->assertDatabaseHas('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id'  => $tip->tip_id,
        ]);
    }

    public function test_invoke_remove()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        UserTechTipBookmark::create([
            'tip_id'  => $tip->tip_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->post(route('tips.bookmark'), ['tip_id' => $tip->tip_id, 'state' => false]);
        $response->assertSuccessful();
        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id'  => $tip->tip_id,
        ]);
    }

    public function test_invoke_add_when_already_there()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        UserTechTipBookmark::create([
            'tip_id'  => $tip->tip_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->post(route('tips.bookmark'), ['tip_id' => $tip->tip_id, 'state' => true]);
        $response->assertStatus(409);
    }
}
