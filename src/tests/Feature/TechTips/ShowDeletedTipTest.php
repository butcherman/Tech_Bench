<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
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

        $response = $this->get(
            route('admin.tech-tips.deleted-tips.show', $techTip->tip_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->ActingAs($user)->get(
            route('admin.tech-tips.deleted-tips.show', $techTip->tip_id)
        );
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->ActingAs($user)->get(
            route('admin.tech-tips.deleted-tips.show', $techTip->tip_id)
        );

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Deleted/Show')
                ->has('tip-data')
                ->has('tip-equipment')
                ->has('tip-files')
                ->has('tip-comments')
            );
    }
}
