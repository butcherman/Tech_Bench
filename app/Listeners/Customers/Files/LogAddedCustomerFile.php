<?php

namespace App\Listeners\Customers\Files;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Files\CustomerFileAddedEvent;

class LogAddedCustomerFile
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileAddedEvent $event)
    {
        Log::channel('cust')->info('New file has been added for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'   => $event->cust->cust_id,
            'file_id'   => $event->file->file_id,
            'file_name' => $event->file->name,
        ]);
    }
}
