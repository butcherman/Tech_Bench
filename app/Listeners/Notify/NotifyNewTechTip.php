<?php

namespace App\Listeners\Notify;

use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Models\UserSettingType;
use App\Events\TechTips\TechTipCreatedEvent;
use App\Notifications\TechTips\NewTechTipNotification;

class NotifyNewTechTip
{
    /**
     * Handle the event
     */
    public function handle(TechTipCreatedEvent $event)
    {
        if($event->notify)
        {
            $notificationType = UserSettingType::where('name', 'Receive Email Notifications')->first();
            $userList = User::whereHas('UserSetting', function($q) use ($notificationType)
            {
                $q->where('setting_type_id', $notificationType->setting_type_id)->where('value', true);
            })->get();

            Notification::send($userList, new NewTechTipNotification($event->techTip));
        }
    }
}
