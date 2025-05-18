<?php

namespace Tests\Feature\TechTip;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TechTipBookmarkTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $techTip = TechTip::factory()->createQuietly();
        $data = [
            'value' => true,
        ];

        $response = $this->post(
            route('tech-tips.bookmark', $techTip->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_add(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $techTip = TechTip::factory()->createQuietly();
        $data = [
            'value' => true,
        ];

        $response = $this->actingAs($user)
            ->post(route(
                'tech-tips.bookmark',
                $techTip->slug
            ), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $techTip->tip_id,
        ]);
    }

    public function test_invoke_add_duplicate(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $techTip = TechTip::factory()->createQuietly();
        $data = [
            'value' => true,
        ];

        $techTip->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $techTip->slug), $data);

        $response->assertStatus(500);
    }

    public function test_invoke_remove(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $techTip = TechTip::factory()->createQuietly();
        $data = [
            'value' => false,
        ];

        $techTip->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $techTip->slug), $data);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $techTip->tip_id,
        ]);
    }

    public function test_invoke_remove_duplicate(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $techTip = TechTip::factory()->createQuietly();
        $data = [
            'value' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.bookmark', $techTip->slug), $data);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $user->user_id,
            'tip_id' => $techTip->tip_id,
        ]);
    }
}
