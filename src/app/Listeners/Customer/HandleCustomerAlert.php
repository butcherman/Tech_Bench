<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CustomerAlertEvent;

class HandleCustomerAlert
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
    public function handle(CustomerAlertEvent $event): void
    {
        //
    }
}
