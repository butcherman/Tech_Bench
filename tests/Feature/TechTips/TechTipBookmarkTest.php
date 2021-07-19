<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipBookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipBookmarkTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tips.bookmark', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_add()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        $data = [
            'tip_id' => $tip->tip_id,
            'state'  => true,
        ];

        $response = $this->actingAs($user)->put(route('tips.bookmark'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('tech_tip_bookmarks', [
            'tip_id'  => $tip->tip_id,
            'user_id' => $user->user_id,
        ]);
    }

    public function test_invoke_remove()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        $data = [
            'tip_id' => $tip->tip_id,
            'state'  => false,
        ];

        TechTipBookmark::create([
            'user_id' => $user->user_id,
            'tip_id'  => $tip->tip_id,
        ]);

        $response = $this->actingAs($user)->put(route('tips.bookmark'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('tech_tip_bookmarks', [
            'tip_id'  => $tip->tip_id,
            'user_id' => $user->user_id,
        ]);
    }
}
