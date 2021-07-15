<?php

namespace App\Listeners;

use App\Events\NewTipCommentEvent;
use App\Models\User;
use App\Models\UserSettingType;
use App\Notifications\EmailNewTechTipComment;
use Illuminate\Support\Facades\Notification;

class NotifyOfNewComment
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
     * @param  NewTipCommentEvent  $event
     * @return void
     */
    public function handle(NewTipCommentEvent $event)
    {
        $notificationType = UserSettingType::where('name', 'Receive Email Notifications')->first();
        $userList = User::whereHas('UserSetting', function($q) use ($notificationType)
        {
            $q->where('setting_type_id', $notificationType->setting_type_id)->where('value', true);
        })->whereHas('TechTipBookmark', function($q) use ($event)
        {
            $q->where('tip_id', $event->tip_id);
        })->get();

        Notification::send($userList, new EmailNewTechTipComment($event));


    }
}
