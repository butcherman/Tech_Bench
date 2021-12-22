<?php

namespace App\Listeners\Work;

use App\Events\Customers\CustomerDeactivatedEvent;
use App\Models\UserCustomerBookmark;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveCustomerBookmarks implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(CustomerDeactivatedEvent $event)
    {
        UserCustomerBookmark::where('cust_id', $event->custData->cust_id)->delete();
    }
}
