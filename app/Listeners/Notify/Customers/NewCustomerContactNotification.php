<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerContactCreatedEvent;
use App\Notifications\Customers\NewContactNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewCustomerContactNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerContactCreatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, $event->user->user_id);

        Log::debug('Preparing to handle Customer Contact Created Event', $userList->toArray());
        Notification::send($userList, new NewContactNotification($event->customer));
    }
}
