<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Customers\Admin\CustomerFileTypeCreatedEvent;

class LogFileTypeCreated
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileTypeCreatedEvent $event)
    {
        Log::channel('cust')->info('New Customer File Type created by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
