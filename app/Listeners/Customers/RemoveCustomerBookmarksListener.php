<?php

namespace App\Listeners\Customers;

use App\Events\Customer\CustomerDeactivatedEvent;
use App\Models\UserCustomerBookmark;
use App\Models\UserCustomerRecent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RemoveCustomerBookmarksListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CustomerDeactivatedEvent $event): void
    {
        UserCustomerRecent::where('cust_id', $event->customer->cust_id)->delete();
        UserCustomerBookmark::where('cust_id', $event->customer->cust_id)->delete();

        Log::debug('Removed all bookmarks and recent visits for Customer '.$event->customer->name);
    }
}
