<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\NewFileNotification;
use App\Events\Customers\Files\CustomerFileAddedEvent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAddedCustomerFile implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileAddedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new NewFileNotification($event->cust));
    }
}
