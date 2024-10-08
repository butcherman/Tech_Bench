<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class ShowDeletedTipTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->get(route('admin.tech-tips.deleted-tips.show', $techTip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->ActingAs(User::factory()->createQuietly())
            ->get(route('admin.tech-tips.deleted-tips.show', $techTip->tip_id));
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.tech-tips.deleted-tips.show', $techTip->tip_id));
        $response->assertSuccessful();
    }
}
