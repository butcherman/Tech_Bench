<?php

namespace Tests\Feature\TechTip;

use App\Events\TechTip\NotifiableCommentEvent;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TechTipCommentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['tech-tips.allow_comments' => true]);
    }

    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.comments.index', $tip->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.index', $tip->slug));

        $response->assertForbidden();
    }

    public function test_index_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.index', $tip->slug));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.comments.index', $tip->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Comments/Index')
                ->has('flagged-comments')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->post(
            route('tech-tips.comments.store', $tip->tip_id),
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
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);

        $response->assertForbidden();
    }

    public function test_store_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);

        $response->assertForbidden();

        config(['tech-tips.allow_comments' => true]);
    }

    public function test_store(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.comment.created'));

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $tip->tip_id,
            'comment' => $data['comment_data'],
        ]);

        Event::assertDispatched(NotifiableCommentEvent::class);
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
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->put(
            route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
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
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();

        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);

        $response->assertForbidden();

        config(['tech-tips.allow_comments' => true]);
    }

    public function test_update_as_admin(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.comment.updated'));

        $this->assertDatabaseHas('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
            'comment' => $data['comment_data'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->delete(
            route('tech-tips.comments.destroy', $comment->comment_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );

        $response->assertForbidden();
    }

    public function test_destroy_as_author(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing(
            'tech_tip_comments',
            $comment->only(['comment_id'])
        );
    }

    public function test_destroy_feature_disabled(): void
    {
        config(['tech-tips.allow_comments' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $comment = TechTipComment::factory()
            ->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );

        $response->assertForbidden();
    }

    public function test_destroy_as_admin(): void
    {
        config(['tech-tips.allow_comments' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing(
            'tech_tip_comments',
            $comment->only(['comment_id'])
        );
    }
}
