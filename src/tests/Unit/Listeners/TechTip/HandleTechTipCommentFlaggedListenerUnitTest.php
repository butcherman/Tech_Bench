<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Mail\TechTip\TechTipCommentFlaggedMail;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleTechTipCommentFlaggedListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();

        $comment = TechTipComment::factory()->create();

        User::factory()->count(10)->create();
        User::factory()->count(2)->create(['role_id' => 1]);

        TechTipCommentFlaggedEvent::dispatch($comment, User::find(1));

        Mail::assertQueued(TechTipCommentFlaggedMail::class);
        Mail::assertQueuedCount(3);
    }
}
