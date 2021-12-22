<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\NewContactNotification;
use App\Events\Customers\Contacts\CustomerContactAddedEvent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAddedCustomerContact implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerContactAddedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new NewContactNotification($event->cust));
    }
}
