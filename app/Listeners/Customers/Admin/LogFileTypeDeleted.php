<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Customers\Admin\CustomerFileTypeDeletedEvent;

class LogFileTypeDeleted
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileTypeDeletedEvent $event)
    {
        Log::channel('cust')->info('Customer File Type deleted by '.Auth::user()->username.'.  Details - ', $event->type->toArray());
    }
}
