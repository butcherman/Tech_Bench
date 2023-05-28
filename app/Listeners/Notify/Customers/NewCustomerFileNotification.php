<?php

namespace App\Listeners\Notify\Customers;

use App\Events\Customer\CustomerFileCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewCustomerFileNotification
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
    public function handle(CustomerFileCreatedEvent $event): void
    {
        //
    }
}
