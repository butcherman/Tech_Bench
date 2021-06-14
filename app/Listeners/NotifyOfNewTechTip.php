<?php

namespace App\Listeners;

use App\Events\NewTechTip;
use App\Models\User;
use App\Models\UserSettingType;
use App\Notifications\EmailNewTechTip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyOfNewTechTip
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
     * @param  NewTechTip  $event
     * @return void
     */
    public function handle(NewTechTip $event)
    {
        //
        $notificationType = UserSettingType::where('name', 'Receive Email Notifications')->first();
        $userList = User::whereHas('UserSetting', function($q) use ($notificationType)
        {
            $q->where('setting_type_id', $notificationType->setting_type_id)->where('value', true);
        })->get();

        Notification::send($userList, new EmailNewTechTip($event->tipData));
    }
}
