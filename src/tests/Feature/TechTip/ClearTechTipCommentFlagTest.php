<?php

namespace Tests\Feature\TechTip;

use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Tests\TestCase;

class ClearTechTipCommentFlagTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->createQuietly()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->get(
            route('tech-tips.comments.restore', $comment->comment_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->createQuietly()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.restore', $comment->comment_id));

        $response->assertForbidden();
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->createQuietly()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.restore', $comment->comment_id));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        config(['tech-tips.allow_comments' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->createQuietly()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.restore', $comment->comment_id));

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Comment Restored');

        $this->assertDatabaseMissing('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
        ]);
    }
}
