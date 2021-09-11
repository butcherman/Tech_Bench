<?php

namespace App\Listeners\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Customer;
use App\Events\Customers\CustomerLinkedEvent;

class LogCustomerLinked
{
    /**
     * Handle the event
     */
    public function handle(CustomerLinkedEvent $event)
    {
        $customer = Customer::with('Parent')->find($event->cust_id);

        if($event->added)
        {
            Log::channel('cust')->info('Customer '.$customer->name.' has been linked to '.$customer->Parent->name.' by '.Auth::user()->username);
        }
        else
        {
            Log::channel('cust')->info('Customer '.$customer->name.' link to parent customer has been removed by '.Auth::user()->username);
        }
    }
}
