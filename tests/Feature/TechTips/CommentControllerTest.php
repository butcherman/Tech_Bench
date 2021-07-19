<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        $data = [
            'tip_id'  => TechTip::factory()->create()->tip_id,
            'comment' => 'This is a random comment',
        ];

        $response = $this->post(route('tips.comments.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_no_permission()
    {
        $data = [
            'tip_id'  => TechTip::factory()->create()->tip_id,
            'comment' => 'This is a random comment',
        ];

        //  Remove the ability to leave a comment on a Tech Tip
        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 27)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->post(route('tips.comments.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'tip_id'  => TechTip::factory()->create()->tip_id,
            'comment' => 'This is a random comment',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('tips.comments.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Comment submitted', 'type' => 'success']);
    }

    /*
    *   Destroy Method
    */
    public function test_destroy_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_different_user()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $user    = User::factory()->create();
        $comment = TechTipComment::factory()->create([
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Comment has been deleted', 'type' => 'danger']);
    }

    public function test_destroy_as_admin()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('tips.comments.destroy', $comment->id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Comment has been deleted', 'type' => 'danger']);
    }
}
