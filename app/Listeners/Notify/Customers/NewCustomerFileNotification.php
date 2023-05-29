<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerFileCreatedEvent;
use App\Notifications\Customers\NewFileNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewCustomerFileNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerFileCreatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, $event->user->user_id);

        Log::debug('Preparing to handle Customer Note Created Event', $userList->toArray());
        Notification::send($userList, new NewFileNotification($event->customer));
    }
}
