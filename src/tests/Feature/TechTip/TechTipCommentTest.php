<?php

namespace Tests\Feature\TechTip;

use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TechTipCommentTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $techTip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->post(
            route('tech-tips.comments.store', $techTip->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        $this->changeRolePermission(4, 'Comment on Tech Tip');

        /** @var User $user */
        $user = User::factory()->create();
        $techTip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $techTip->slug), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $techTip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $techTip->slug), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $techTip->tip_id,
            'comment' => $data['comment_data'],
        ]);

        Event::assertDispatched(NotifiableTipCommentEvent::class);
    }

    public function test_store_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $techTip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $techTip->slug), $data);

        $response->assertForbidden();
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        $this->changeRolePermission(4, 'Comment on Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update_different_user(): void
    {
        $this->changeRolePermission(4, 'Comment on Tech Tip');

        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);
        $invalid = TechTip::factory()->create();
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)->put(
            route('tech-tips.comments.update', [
                $invalid->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertStatus(404);
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('Comment Updated'));

        $this->assertDatabaseHas('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
            'comment' => $data['comment_data'],
        ]);
    }

    public function test_update_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);
        $data = [
            'comment_data' => 'this is a comment.',
        ];

        $response = $this->actingAs($user)->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]),
            $data
        );

        $response->assertForbidden();
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->delete(route('tech-tips.comments.destroy', [
            $comment->TechTip->slug,
            $comment->comment_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)->delete(route('tech-tips.comments.destroy', [
            $comment->TechTip->slug,
            $comment->comment_id,
        ]));

        $response->assertForbidden();
    }

    public function test_destroy_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create();
        $invalid = TechTip::factory()->create();

        $response = $this->actingAs($user)->delete(route('tech-tips.comments.destroy', [
            $invalid->slug,
            $comment->comment_id,
        ]));

        $response->assertStatus(404);
    }

    public function test_destroy_as_admin(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('tech-tips.comments.destroy', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
        ]);
    }

    public function test_destroy_own_comment(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('tech-tips.comments.destroy', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
        ]);
    }

    public function test_destroy_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('tech-tips.comments.destroy', [
                $comment->TechTip->slug,
                $comment->comment_id,
            ]));

        $response->assertForbidden();
    }
}
