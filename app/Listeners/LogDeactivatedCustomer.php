<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\CustomerDeactivatedEvent;

class LogDeactivatedCustomer
{
    /**
     * Handle the event
     */
    public function handle(CustomerDeactivatedEvent $event)
    {
        Log::channel('cust')->notice('User '.Auth::user()->username.' has Deactivated Customer '.$event->custData->name.'.  Details - ', [
            $event->custData->toArray(),
        ]);
    }
}
