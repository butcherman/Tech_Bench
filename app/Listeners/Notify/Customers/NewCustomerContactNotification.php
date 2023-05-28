<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerContactCreatedEvent;
use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\NewContactNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewCustomerContactNotification
{
    /**
     * Handle the event.
     */
    public function handle(CustomerContactCreatedEvent $event): void
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->customer->cust_id)->get()->pluck('user_id')->toArray();
        $userList = User::find($bookmarks);

        Log::debug('Preparing to handle Customer Contact Created Event', $userList->toArray());
        Notification::send($userList, new NewContactNotification($event->customer));
    }
}
