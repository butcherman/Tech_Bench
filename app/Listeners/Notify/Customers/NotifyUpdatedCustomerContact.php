<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\UpdatedContactNotification;
use App\Events\Customers\Contacts\CustomerContactUpdatedEvent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUpdatedCustomerContact implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerContactUpdatedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new UpdatedContactNotification($event->cust));
    }
}
