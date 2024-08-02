<?php

namespace App\Listeners\Notify\TechTips;

use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Models\User;
use App\Notifications\TechTips\CommentFlaggedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyOfFlaggedTipListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TipCommentFlaggedEvent $event): void
    {
        $userList = User::where('role_id', '<=', 2)->get();

        Log::debug('Tech Tip Comment Flagged notification queuing for delivery', $userList->toArray());

        Notification::send($userList, new CommentFlaggedNotification($event->comment));
    }
}
