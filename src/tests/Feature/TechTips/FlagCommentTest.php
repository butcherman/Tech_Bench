<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlagCommentTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->post(
            route('tech-tips.comments.flag', [
                $comment->TechTip->tip_id,
                $comment->comment_id
            ])
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('tech-tips.comments.flag', [
                $comment->TechTip->tip_id,
                $comment->comment_id
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('tips.comment.flagged'));

        $this->assertDatabaseHas('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
        ]);
    }

    public function test_invoke_second_flag_attempt()
    {
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'comment_id' => $comment->comment_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.flag', [
                $comment->TechTip->tip_id,
                $comment->comment_id
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas(
            'warning',
            'You have already flagged this comment'
        );
    }
}
