<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Notifications\TechTip\NewTipCommentNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class HandleNotifiableTipCommentListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle();
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Notification::fake();

        User::factory()->count(10)->create();
        $techTip = TechTip::factory()->create();
        $comment = TechTipComment::factory()->create(['tip_id' => $techTip->tip_id]);

        NotifiableTipCommentEvent::dispatch($comment);

        Notification::assertSentTimes(NewTipCommentNotification::class, User::all()->count() - 1);

        Notification::assertNotSentTo(User::find($comment->user_id), NewTipCommentNotification::class);
    }
}
