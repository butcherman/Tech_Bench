<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerEquipmentUpdatedEvent;
use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\UpdatedEquipmentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdatedCustomerEquipmentNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CustomerEquipmentUpdatedEvent $event): void
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->customer->cust_id)->get()->pluck('user_id')->toArray();
        $userList = User::find($bookmarks);

        Log::debug('Preparing to handle Customer Equipment Created Event', $userList->toArray());
        Notification::send($userList, new UpdatedEquipmentNotification($event->customer));
    }
}
