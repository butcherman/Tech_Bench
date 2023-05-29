<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerEquipmentUpdatedEvent;
use App\Notifications\Customers\UpdatedEquipmentNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdatedCustomerEquipmentNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerEquipmentUpdatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, $event->user->user_id);

        Log::debug('Preparing to handle Customer Equipment Created Event', $userList->toArray());
        Notification::send($userList, new UpdatedEquipmentNotification($event->customer));
    }
}
