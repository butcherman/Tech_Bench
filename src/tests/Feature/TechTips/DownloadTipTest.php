<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadTipTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.download', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.download', $tip->slug));
        $response->assertSuccessful();
    }
}
