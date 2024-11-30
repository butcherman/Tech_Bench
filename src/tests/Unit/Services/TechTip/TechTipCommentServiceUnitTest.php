<?php

namespace Tests\Unit\Services\TechTip;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Services\TechTip\TechTipCommentService;
use Tests\TestCase;

class TechTipCommentServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createComment()
    |---------------------------------------------------------------------------
    */
    public function test_create_comment(): void
    {
        $techTip = TechTip::factory()->create();
        $user = User::factory()->create();

        $data = [
            'comment_data' => 'This is a comment',
        ];

        $testObj = new TechTipCommentService;
        $res = $testObj->createComment(collect($data), $techTip, $user);

        $this->assertEquals($res->comment, $data['comment_data']);

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $techTip->tip_id,
            'user_id' => $user->user_id,
            'comment' => $data['comment_data'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateComment()
    |---------------------------------------------------------------------------
    */
    public function test_update_comment(): void
    {
        $techTip = TechTip::factory()->create();
        $comment = TechTipComment::factory()->create([
            'tip_id' => $techTip->tip_id,
        ]);

        $data = [
            'comment_data' => 'This is a comment',
        ];

        $testObj = new TechTipCommentService;
        $res = $testObj->updateComment(collect($data), $comment);

        $this->assertEquals($res->comment, $data['comment_data']);

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $techTip->tip_id,
            'comment_id' => $comment->comment_id,
            'comment' => $data['comment_data'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyComment()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_comment(): void
    {
        $comment = TechTipComment::factory()->create();

        $testObj = new TechTipCommentService;
        $testObj->destroyComment($comment);

        $this->assertDatabaseMissing(
            'tech_tip_comments',
            $comment->makeHidden(['author', 'is_flagged'])->toArray()
        );
    }
}
