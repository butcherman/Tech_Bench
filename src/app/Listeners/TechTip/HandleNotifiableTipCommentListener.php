<?php

namespace App\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Models\User;
use App\Notifications\TechTip\NewTipCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HandleNotifiableTipCommentListener implements ShouldQueue
{
    /**
     * Send Notification to others that have commented or bookmarked the Tech
     * Tip of the new or updated Tech Tip Comment.
     */
    public function handle(NotifiableTipCommentEvent $event): void
    {
        Log::debug('Handling Notifiable Tech Tip Comment Event', [
            'comment_id' => $event->comment->comment_id
        ]);

        $userList = User::whereNot('user_id', $event->comment->user_id)->get();

        Notification::send(
            $userList,
            new NewTipCommentNotification($event->comment)
        );
    }
}
