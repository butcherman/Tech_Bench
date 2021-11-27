<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipUpdatedEvent;
use App\Models\User;
use App\Models\UserSettingType;
use App\Notifications\TechTips\UpdatedTechTipNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUpdatedTechTip
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TechTipUpdatedEvent  $event
     * @return void
     */
    public function handle(TechTipUpdatedEvent $event)
    {
        if($event->notify)
        {
            $notificationType = UserSettingType::where('name', 'Receive Email Notifications')->first();
            $userList = User::whereHas('UserSetting', function($q) use ($notificationType)
            {
                $q->where('setting_type_id', $notificationType->setting_type_id)->where('value', true);
            })->get();

            Notification::send($userList, new UpdatedTechTipNotification($event->techTip));
        }
    }
}
