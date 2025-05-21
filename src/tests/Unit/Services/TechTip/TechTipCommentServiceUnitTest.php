<?php

namespace Tests\Unit\Services\TechTip;

use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Notification;
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
        Event::fake();

        $user = User::factory()->create();
        $techTip = TechTip::factory()->create();
        $data = [
            'comment_data' => 'This is a new comment to a Tech Tip',
        ];

        $testObj = new TechTipCommentService;
        $res = $testObj->createComment(collect($data), $techTip, $user);

        $this->assertEquals($res->comment, $data['comment_data']);

        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id' => $techTip->tip_id,
            'comment' => $data['comment_data'],
            'user_id' => $user->user_id,
        ]);


        Event::assertDispatched(NotifiableTipCommentEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateComment()
    |---------------------------------------------------------------------------
    */
    public function test_update_comment(): void
    {
        $comment = TechTipComment::factory()->create();
        $data = [
            'comment_data' => 'This is a new comment to a Tech Tip',
        ];

        $testObj = new TechTipCommentService;
        $res = $testObj->updateComment(collect($data), $comment);

        $this->assertEquals($res->comment, $data['comment_data']);

        $this->assertDatabaseHas('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
            'tip_id' => $comment->tip_id,
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

        $this->assertDatabaseMissing('tech_tip_comments', [
            'comment_id' => $comment->comment_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | flagComment()
    |---------------------------------------------------------------------------
    */
    public function test_flag_comment(): void
    {
        Event::fake();

        $comment = TechTipComment::factory()->create();
        $user = User::factory()->create();

        $testObj = new TechTipCommentService;
        $testObj->flagComment($comment, $user);

        $this->assertDatabaseHas('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id,
            'user_id' => $user->user_id,
        ]);

        Event::assertDispatched(TechTipCommentFlaggedEvent::class);
    }

    public function test_flag_comment_already_flagged(): void
    {
        Exceptions::fake();
        Event::fake();

        $comment = TechTipComment::factory()->create();
        $user = User::factory()->create();
        $comment->flagComment($user);

        $testObj = new TechTipCommentService;
        $testObj->flagComment($comment, $user);

        Exceptions::assertReported(UniqueConstraintViolationException::class);
        Event::assertNotDispatched(TechTipCommentFlaggedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | releaseComment()
    |---------------------------------------------------------------------------
    */
    public function test_release_comment(): void
    {
        $comment = TechTipComment::factory()->create();
        $user = User::factory()->create();
        $comment->flagComment($user);

        $testObj = new TechTipCommentService;
        $testObj->releaseComment($comment);

        $this->assertDatabaseMissing('tech_tip_comment_flags', [
            'comment_id' => $comment->comment_id
        ]);
    }
}
