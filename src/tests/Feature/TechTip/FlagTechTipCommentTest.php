<?php

namespace Tests\Feature\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FlagTechTipCommentTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->post(
            route('tech-tips.comments.flag', [
                $comment->comment_id,
            ])
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.flag', [
                $comment->comment_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('tips.comment.flagged'));

        $this->assertDatabaseHas('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
        ]);

        Event::assertDispatched(TechTipCommentFlaggedEvent::class);
    }

    public function test_invoke_second_flag_attempt(): void
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
                $comment->comment_id,
            ]));
        $response->assertStatus(302);
    }
}
