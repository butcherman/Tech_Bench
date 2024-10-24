<?php

namespace Tests\Feature\TechTips;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowFlaggedCommentTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('tech-tips.comments.show-flagged'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.show-flagged'));

        $response->assertForbidden();
    }

    public function test_invoke_feature_disabled()
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.show-flagged'));

        $response->assertForbidden();
    }

    public function test_invoke()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.show-flagged'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Comments/Index')
                ->has('flagged-comments')
            );
    }
}
