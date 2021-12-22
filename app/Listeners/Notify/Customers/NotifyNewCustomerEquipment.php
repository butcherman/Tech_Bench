<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\NewEquipmentNotification;
use App\Events\Customers\Equipment\CustomerEquipmentAddedEvent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyNewCustomerEquipment implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentAddedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new NewEquipmentNotification($event->cust));
    }
}
