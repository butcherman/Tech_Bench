<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerFileUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatedCustomerFileNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CustomerFileUpdatedEvent $event): void
    {
        //
    }
}
