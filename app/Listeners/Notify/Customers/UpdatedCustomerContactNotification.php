<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerContactUpdatedEvent;
use App\Models\User;
use App\Models\UserCustomerBookmark;
use App\Notifications\Customers\UpdatedContactNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdatedCustomerContactNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CustomerContactUpdatedEvent $event): void
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $event->customer->cust_id)->get()->pluck('user_id')->toArray();
        $userList = User::find($bookmarks);

        Log::debug('Preparing to handle Customer Contact Updated Event', $userList->toArray());
        Notification::send($userList, new UpdatedContactNotification($event->customer));
    }
}
