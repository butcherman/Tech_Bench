<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Listeners\TechTip\HandleTechTipCommentFlaggedListener;
use App\Mail\TechTip\TechTipCommentFlaggedMail;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleTechTipCommentFlaggedListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle();
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();

        $userList = User::factory()->count(10)->create();

        $comment = TechTipComment::factory()->create();
        $comment->flagComment(User::factory()->create());
        $event = new TechTipCommentFlaggedEvent($comment, $userList[0]);

        $listener = new HandleTechTipCommentFlaggedListener($event);
        $listener->handle($event);

        foreach ($userList as $user) {
            Mail::assertNotQueued(TechTipCommentFlaggedMail::class, $user->email);
        }

        Mail::assertQueued(TechTipCommentFlaggedMail::class, User::find(1)->email);
    }
}
