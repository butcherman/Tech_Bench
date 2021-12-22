<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipCommentFlaggedEvent;
use App\Models\User;
use App\Notifications\FlaggedTechTipCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotifyOfFlaggedComment implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentFlaggedEvent $event)
    {
        $userList = User::where('role_id', '<=', 2)->get();     //  TODO - should be for users who can manage Tech Tips
        Notification::send($userList, new FlaggedTechTipCommentNotification($event->comment, Auth::user()));
    }
}
