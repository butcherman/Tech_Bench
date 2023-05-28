<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerEquipmentCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\NewEquipmentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NewCustomerEquipmentNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CustomerEquipmentCreatedEvent $event): void
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->customer->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Log::debug('Preparing to handle Customer Equipment Created Event', $userList->toArray());
        Notification::send($userList, new NewEquipmentNotification($event->customer));
    }
}
