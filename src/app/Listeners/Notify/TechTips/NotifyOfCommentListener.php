<?php

namespace App\Listeners\Notify\TechTips;

use App\Events\TechTips\TipCommentedEvent;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use App\Notifications\TechTips\TipCommentedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyOfCommentListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TipCommentedEvent $event): void
    {
        // Comment goes to the author and updated person if needed
        $author = [
            $event->comment->TechTip->user_id,
            $event->comment->TechTip->updated_id,
        ];

        // Other users who have commented will also get notification
        $comments = $event->comment
            ->TechTip
            ->TechTipComment
            ->pluck('user_id')
            ->toArray();

        // Merge lists and remove duplicates and  Comment Author
        $userArr = array_unique(array_merge($author, $comments));
        unset($userArr[array_search($event->comment->user_id, $userArr)]);

        // Get Users
        $userList = User::whereIn('user_id', $userArr)->get();

        Notification::send($userList, new TipCommentedNotification($event->comment));
    }
}
