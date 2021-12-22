<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Customers\Admin\CustomerFileTypeUpdatedEvent;

class LogFileTypeUpdated
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileTypeUpdatedEvent $event)
    {
        Log::channel('cust')->info('Customer File Type updated by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
