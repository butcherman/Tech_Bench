<?php

namespace App\Listeners;

use App\Events\FlaggedTipCommentEvent;
use App\Models\User;
use App\Notifications\EmailAdminForFlaggedComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyOfFlaggedComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Handle the event.
     *
     * @param  FlaggedTipCommentEvent  $event
     * @return void
     */
    public function handle(FlaggedTipCommentEvent $event)
    {
        //

        // dd($event);

        $userList = User::where('role_id', '<', 3)->get();
        Notification::send($userList, new EmailAdminForFlaggedComment($event->comment, $event->flaggedBy));
    }
}
