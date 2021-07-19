<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlagCommentTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->get(route('tips.comments.flag', $comment->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke()
    {
        $comment = TechTipComment::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tips.comments.flag', $comment->id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Comment has been flagged as inappropriate and will be reviewed by a System Administrator', 'type' => 'danger']);
    }
}
