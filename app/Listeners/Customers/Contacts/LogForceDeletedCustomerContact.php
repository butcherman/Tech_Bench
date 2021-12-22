<?php

namespace App\Listeners\Customers\Contacts;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Contacts\CustomerContactForceDeletedEvent;

class LogForceDeletedCustomerContact
{
    /**
     * Handle the event
     */
    public function handle(CustomerContactForceDeletedEvent $event)
    {
        Log::channel('cust')->info('Customer Contact ID '.$event->cont->cont_id.' permanently delted for Customer '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'Contact Name' => $event->cont->name,
            'Contact ID'   => $event->cont->cont_id,
            'Customer ID'  => $event->cust->cust_id,
        ]);
    }
}
