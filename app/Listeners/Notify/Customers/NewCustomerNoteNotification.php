<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerNoteCreatedEvent;
use App\Notifications\Customers\NewNoteNotification;
use App\Traits\CustomerEventsTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewCustomerNoteNotification implements ShouldQueue
{
    use CustomerEventsTrait;

    /**
     * Handle the event.
     */
    public function handle(CustomerNoteCreatedEvent $event): void
    {
        $userList = $this->getUserList($event->customer->cust_id, 1);

        Log::debug('Preparing to handle Customer Note Created Event', $userList->toArray());
        Notification::send($userList, new NewNoteNotification($event->customer));
    }
}
