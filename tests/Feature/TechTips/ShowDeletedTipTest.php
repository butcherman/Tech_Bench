<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowDeletedTipTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();
        $tip->delete();

        $response = $this->get(route('tips.show-deleted', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $tip = TechTip::factory()->create();
        $tip->delete();

        $response = $this->actingAs(User::factory()->create())->get(route('tips.show-deleted', $tip->slug));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $tip = TechTip::factory()->create();
        $tip->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('tips.show-deleted', $tip->slug));
        $response->assertSuccessful();
    }
}
