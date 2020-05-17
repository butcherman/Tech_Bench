<?php

namespace Tests\Unit\TechTips;

use Tests\TestCase;

use Illuminate\Support\Facades\Notification;

use App\Domains\TechTips\SetTechTipComments;

use App\Http\Requests\TechTipNewCommentRequest;

use App\TechTips;
use App\TechTipComments;


class SetTechTipCommentsTest extends TestCase
{
    protected $testTip, $testObj;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testTip      = factory(TechTips::class)->create();
        $this->testObj      = new SetTechTipComments;
    }

    //  Test creating a new tech tip comment
    public function test_create_tip_comment()
    {
        Notification::fake();

        $data = new TechTipNewCommentRequest([
            'comment' => $comment = 'This is a test comment',
            'tip_id'  => $this->testTip->tip_id,
        ]);

        $result = $this->actingAs($user = $this->getTech())->testObj->createTipComment($data);
        $this->assertTrue($result);
        $this->assertDatabaseHas('tech_tip_comments', [
            'tip_id'  => $this->testTip->tip_id,
            'comment' => $comment,
            'user_id' => $user->user_id,
        ]);
    }

    //  Test that a comment can be deleted
    public function test_delete_tip_comment()
    {
        $comment = factory(TechTipComments::class)->create();

        $result = $this->actingAs($this->getTech())->testObj->deleteTipComment($comment->comment_id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('tech_tip_comments', $comment->toArray());
    }
}
