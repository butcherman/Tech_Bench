<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableCommentEvent;
use App\Listeners\TechTip\HandleNotifiableCommentListener;
use App\Mail\TechTip\NewCommentMail;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleNotifiableCommentListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();

        $userList = User::factory()->count(10)->create();

        $comment = TechTipComment::factory()->create();
        $event = new NotifiableCommentEvent($comment, CrudAction::Create);

        $listener = new HandleNotifiableCommentListener;
        $listener->handle($event);

        foreach ($userList as $user) {
            Mail::assertQueued(NewCommentMail::class, $user->email);
        }

        Mail::assertNotQueued(NewCommentMail::class, $comment->User->email);
    }
}
