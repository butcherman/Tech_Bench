<?php

namespace Tests\Feature\TechTips;

use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Illuminate\Support\Facades\Event;
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
                $comment->comment_id,
            ])
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.flag', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('tips.comment.flagged'));

        $this->assertDatabaseHas('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
        ]);

        Event::assertDispatched(TipCommentFlaggedEvent::class);
    }

    public function test_invoke_second_flag_attempt()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'comment_id' => $comment->comment_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.flag', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas(
            'warning',
            'You have already flagged this comment'
        );
    }
}
