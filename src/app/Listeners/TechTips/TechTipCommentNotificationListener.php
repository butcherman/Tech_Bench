<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TipCommentedEvent;
use App\Models\User;
use App\Notifications\TechTips\TipCommentedNotification;
use Illuminate\Support\Facades\Notification;

class TechTipCommentNotificationListener
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
