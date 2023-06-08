<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerContactUpdatedEvent;
use App\Notifications\Customers\UpdatedContactNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdatedCustomerContactNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerContactUpdatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, $event->user->user_id);

        Log::debug('Preparing to handle Customer Contact Updated Event', $userList->toArray());
        Notification::send($userList, new UpdatedContactNotification($event->customer, $event->contact));
    }
}
