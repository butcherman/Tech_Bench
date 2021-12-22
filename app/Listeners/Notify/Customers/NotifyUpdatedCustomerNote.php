<?php

namespace App\Listeners\Notify\Customers;

use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Events\Customers\Notes\CustomerNoteUpdatedEvent;
use App\Notifications\Customers\UpdatedNoteNotification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUpdatedCustomerNote implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerNoteUpdatedEvent $event)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->cust->cust_id)->get()->pluck('user_id')->toArray();
        $userList  = User::find($bookmarks);

        Notification::send($userList, new UpdatedNoteNotification($event->cust));
    }
}
