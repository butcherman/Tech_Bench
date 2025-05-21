<?php

namespace Tests\Feature\TechTip;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DisabledTipViewTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
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

    public function test_invoke_no_permission(): void
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

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $techTip = TechTip::factory()->create();
        $techTip->delete();

        $response = $this->ActingAs($user)->get(
            route('admin.tech-tips.deleted-tips.show', $techTip->tip_id)
        );

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Admin/Deleted/Show')
                    ->has('tech-tip')
                    ->has('equipment')
                    ->has('files')
            );
    }
}
