<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerNoteUpdatedEvent;
use App\Notifications\Customers\UpdatedNoteNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdatedCustomerNoteNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerNoteUpdatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, 1);

        Log::debug('Preparing to handle Customer Note Created Event', $userList->toArray());
        Notification::send($userList, new UpdatedNoteNotification($event->customer));
    }
}
