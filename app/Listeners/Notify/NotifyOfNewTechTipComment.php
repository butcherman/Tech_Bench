<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipCommentCreatedEvent;
use App\Models\TechTip;
use App\Models\User;
use App\Models\UserTechTipBookmark;
use App\Notifications\NewTechTipCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotifyOfNewTechTipComment
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentCreatedEvent $event)
    {
        $userList = [];

        $userList[] = TechTip::find($event->comment->tip_id)->first()->user_id;
        $bookmarks  = UserTechTipBookmark::where('tip_id', $event->comment->tip_id)->get()->toArray();

        $userList = User::find(array_merge($userList, $bookmarks));
        Notification::send($userList, new NewTechTipCommentNotification($event->comment, Auth::user()));
    }
}
