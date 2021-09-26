<?php

namespace App\Listeners\Customers\Admin;

use App\Events\Customers\Admin\CustomerIdChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogChangedCustomerId
{
    /**
     * Handle the event
     */
    public function handle(CustomerIdChangedEvent $event)
    {
        Log::channel('cust')->notice('Customer ID changed for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'name'   => $event->cust->name,
            'old_id' => $event->oldId,
            'new_id' => $event->cust->cust_id,
        ]);
    }
}
