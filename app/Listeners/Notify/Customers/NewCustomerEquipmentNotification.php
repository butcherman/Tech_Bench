<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerEquipmentCreatedEvent;
use App\Notifications\Customers\NewEquipmentNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewCustomerEquipmentNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerEquipmentCreatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, $event->user->user_id);

        Log::debug('Preparing to handle Customer Equipment Created Event', $userList->toArray());
        Notification::send($userList, new NewEquipmentNotification($event->customer));
    }
}
