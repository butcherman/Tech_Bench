<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipCommentsTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('tips.comments.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('tips.comments.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('tips.comments.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'comment' => 'This is a super cool comment',
            'tip_id'  => TechTip::factory()->create(),
        ];

        $response = $this->post(route('tips.comments.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 27)->update(['allow' => false]);
        $user    = User::factory()->create(['role_id' => 4]);
        $comment = TechTipComment::factory()->create();
        $data    = [
            'comment'    => 'This is a super cool Comment',
            'tip_id'  => TechTip::factory()->create()->tip_id,
        ];

        $response = $this->actingAs($user)->post(route('tips.comments.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'comment' => 'This is a super cool comment',
            'tip_id'  => TechTip::factory()->create()->tip_id,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('tips.comments.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tech_tip_comments', $data);
    }

    /**
     * Show Method - this will unflag a comment that was previously flagged
     */
    public function test_show_guest()
    {
        $comment = TechTipComment::factory()->create(['flagged' => true]);

        $response = $this->get(route('tips.comments.show', $comment->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $comment = TechTipComment::factory()->create(['flagged' => true]);

        $response = $this->actingAs(User::factory()->create())->get(route('tips.comments.show', $comment->id));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $comment = TechTipComment::factory()->create(['flagged' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('tips.comments.show', $comment->id));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Comment has been unflagged',
            'type'    => 'warning',
        ]);
        $this->assertDatabaseHas('tech_tip_comments', [
            'id'      => $comment->id,
            'comment' => $comment->comment,
            'flagged' => false,
        ]);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->get(route('tips.comments.edit', $comment->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit()
    {
        $comment = TechTipComment::factory()->create();
        $result  = $comment->only(['id']);
        $result['flagged'] = true;

        $response = $this->actingAs(User::factory()->create())->get(route('tips.comments.edit', $comment->id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('tech_tip_comments', $result);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $comment = TechTipComment::factory()->create();
        $data    = [
            'comment'    => 'This is an updated Comment',
            'comment_id' => $comment->id,
        ];

        $response = $this->put(route('tips.comments.update', $comment->id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);
        $data    = [
            'comment'    => 'This is an updated Comment',
            'comment_id' => $comment->id,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('tips.comments.update', $comment->id), $data);
        $response->assertStatus(403);
    }

    public function test_update_as_owner()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);
        $data    = [
            'comment'    => 'This is an updated Comment',
            'comment_id' => $comment->id,
        ];

        $response = $this->actingAs($user)->put(route('tips.comments.update', $comment->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tech_tip_comments', [
            'id'      => $comment->id,
            'comment' => $data['comment'],
        ]);
    }

    public function test_update_as_admin()
    {
        $comment = TechTipComment::factory()->create();
        $data    = [
            'comment'    => 'This is an updated Comment',
            'comment_id' => $comment->id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('tips.comments.update', $comment->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tech_tip_comments', [
            'id'      => $comment->id,
            'comment' => $data['comment'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(403);
    }

    public function test_destroy_as_owner()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['id', 'comment']));
    }

    public function test_destroy_as_admin()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['id', 'comment']));
    }
}
