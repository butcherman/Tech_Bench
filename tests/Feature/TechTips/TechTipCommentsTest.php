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

    //  TODO - Why doesn't this work?
    // public function test_store_no_permission()
    // {
    //     $data = [
    //         'comment' => 'This is a super cool comment',
    //         'tip_id'  => TechTip::factory()->create()->tip_id,
    //     ];
    //     $permId = UserRolePermissionTypes::where('description', 'Comment on Tech Tip')->first()->perm_type_id;
    //     UserRolePermissions::where('perm_type_id', $permId)->where('role_id', 4)->update(['allow' => false]);

    //     $response = $this->actingAs(User::factory()->create(['role_id', 4]))->post(route('tips.comments.store'), $data);
    //     $response->assertSuccessful();
    // }

    public function test_store()
    {
        $data = [
            'comment' => 'This is a super cool comment',
            'tip_id'  => TechTip::factory()->create()->tip_id,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('tips.comments.store'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('tech_tip_comments', $data);
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
        $response->assertSuccessful();
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

    //  TODO - Fix???
    // public function test_update_no_permission()
    // {
    //     $comment = TechTipComment::factory()->create();
    //     $data    = [
    //         'comment'    => 'This is an updated Comment',
    //         'comment_id' => $comment->id,
    //     ];

    //     $response = $this->put(route('tips.comments.update', $comment->id), $data);
    //     dd($response->getSession());
    //     $response->assertStatus(403);
    // }

    public function test_update_as_owner()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);
        $data    = [
            'comment'    => 'This is an updated Comment',
            'comment_id' => $comment->id,
        ];

        $response = $this->actingAs($user)->put(route('tips.comments.update', $comment->id), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('tech_tip_comments', [
            'id'      => $comment->id,
            'comment' => $data['comment'],
        ]);
    }

    //  TODO - Fix
    // public function test_update_as_admin()
    // {
    //     $comment = TechTipComment::factory()->create();
    //     $data    = [
    //         'comment'    => 'This is an updated Comment',
    //         'comment_id' => $comment->id,
    //     ];

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('tips.comments.update', $comment->id), $data);
    //     $response->assertSuccessful();
    //     $this->assertDatabaseHas('tech_tip_comments', [
    //         'id'      => $comment->id,
    //         'comment' => $data['comment'],
    //     ]);
    // }

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

    //  TODO - Fix???
    // public function test_destroy_no_permission()
    // {
    //     $comment = TechTipComment::factory()->create();

    //     $response = $this->actingAs(User::factory()->create())->delete(route('tips.comments.destroy', $comment->id));
    //     $response->assertStatus(403);
    // }

    public function test_destroy_as_owner()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->delete(route('tips.comments.destroy', $comment->id));
        $response->assertSuccessful();
        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['id', 'comment']));
    }

    public function test_destroy_as_admin()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('tips.comments.destroy', $comment->id));
        $response->assertSuccessful();
        $this->assertDatabaseMissing('tech_tip_comments', $comment->only(['id', 'comment']));
    }
}
