<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use App\Notifications\TechTips\TipCommentedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TechTipCommentTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertForbidden();
    }

    public function test_index_feature_disabled()
    {
        config(['techTips.allow_comments' => false]);

        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertForbidden();
    }

    public function test_index()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.index', $tip->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->post(
            route('tech-tips.comments.store', $tip->tip_id),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $permId = UserRolePermissionType::where('description', 'Comment on Tech Tip')
            ->first()->perm_type_id;
        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', $permId)
            ->update([
                'allow' => false,
            ]);

        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);
        $response->assertForbidden();
    }

    public function test_store_feature_disabled()
    {
        config(['techTips.allow_comments' => false]);
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);
        $response->assertForbidden();
    }

    public function test_store()
    {
        Notification::fake();

        config(['techTips.allow_comments' => true]);
        $tip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a comment',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('tech-tips.comments.store', $tip->tip_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.comment.created'));

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $tip->tip_id,
            'comment' => $data['comment_data'],
        ]);

        Notification::assertSentTo(
            User::find($tip->user_id),
            TipCommentedNotification::class
        );
    }

    /**
     * Update Method
     */
    public function test_update_guest()
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
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $permId = UserRolePermissionType::where('description', 'Comment on Tech Tip')
            ->first()->perm_type_id;
        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', $permId)
            ->update([
                'allow' => false,
            ]);

        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);
        $response->assertForbidden();
    }

    public function test_update_feature_disabled()
    {
        config(['techTips.allow_comments' => false]);
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);
        $response->assertForbidden();
    }

    public function test_update_as_admin()
    {
        config(['techTips.allow_comments' => true]);
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is an updated comment',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('tech-tips.comments.update', [
                $comment->TechTip->tip_id,
                $comment->comment_id,
            ]), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $user = User::factory()->create();
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
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.comment.updated'));

        $this->assertDatabaseHas('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
            'comment' => $data['comment_data'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->delete(
            route('tech-tips.comments.destroy', $comment->comment_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );
        $response->assertForbidden();
    }

    public function test_destroy_as_author()
    {
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );
        $response->assertStatus(302);
        $response->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['comment_id']));
    }

    public function test_destroy_feature_disabled()
    {
        config(['techTips.allow_comments' => false]);
        $user = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );
        $response->assertForbidden();
    }

    public function test_destroy_as_admin()
    {
        config(['techTips.allow_comments' => true]);
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(
                route('tech-tips.comments.destroy', $comment->comment_id)
            );
        $response->assertStatus(302);
        $response->assertSessionHas('warning', 'Comment Deleted');

        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['comment_id']));
    }

    /**
     * Restore Method
     */
    public function test_restore_guest()
    {
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->create()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->get(
            route('tech-tips.comments.restore', $comment->comment_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->create()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('tech-tips.comments.restore', $comment->comment_id));
        $response->assertForbidden();
    }

    public function test_restore_feature_disabled()
    {
        config(['techTips.allow_comments' => false]);
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->create()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.restore', $comment->comment_id));
        $response->assertForbidden();
    }

    public function test_restore()
    {
        config(['techTips.allow_comments' => true]);
        $comment = TechTipComment::factory()->create();
        TechTipCommentFlag::create([
            'user_id' => User::factory()->create()->user_id,
            'comment_id' => $comment->comment_id,
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('tech-tips.comments.restore', $comment->comment_id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
        ]);
    }
}
