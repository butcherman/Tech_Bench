<?php

namespace Tests\Unit\TechTips;

use App\Domains\TechTips\SetTechTips;
use App\Domains\TechTips\SetTipComments;
use App\Http\Requests\TechTips\NewCommentRequest;
use App\Notifications\NewTechTipNotification;
use App\TechTipComments;
use App\TechTips;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SetTipCommentsTest extends TestCase
{
    public function test_create_tip_comment()
    {
        $owner = $this->getTech();
        $user  = $this->getTech();
        $tip   = factory(TechTips::class)->create([
            'user_id' => $owner->user_id,
        ]);
        $comment = factory(TechTipComments::class)->make();
        $data = new NewCommentRequest([
            'tip_id'  => $tip->tip_id,
            'comment' => $comment->comment,
        ]);

        $res = (new SetTipComments)->createTipComment($data, $user);
        $this->assertTrue($res);
        $this->assertDatabaseHas('tech_tip_comments', $data->toArray());
        // Notification::assertSentTo($owner, NewTechTipNotification::class);
    }

    public function test_delete_comment()
    {
        $comment = factory(TechTipComments::class)->create();

        $res = (new SetTipComments)->deleteComment($comment->comment_id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('tech_tip_comments', ['comment_id' => $comment->comment_id]);
    }
}
