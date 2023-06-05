<?php

namespace App\Listeners\Customers;

use App\Events\Customer\CustomerVisitedEvent;
use App\Models\UserCustomerRecent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddRecentCustomerEntry
{
    /**
     * Handle the event.
     */
    public function handle(CustomerVisitedEvent $event): void
    {
        UserCustomerRecent::firstOrCreate([
            'cust_id' => $event->customer->cust_id,
            'user_id' => $event->user->user_id,
        ])->touch();
    }
}
