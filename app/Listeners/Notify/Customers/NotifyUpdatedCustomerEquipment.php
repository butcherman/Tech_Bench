<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\UpdatedEquipmentNotification;
use App\Events\Customers\Equipment\CustomerEquipmentUpdatedEvent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUpdatedCustomerEquipment implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerEquipmentUpdatedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new UpdatedEquipmentNotification($event->cust));
    }
}
