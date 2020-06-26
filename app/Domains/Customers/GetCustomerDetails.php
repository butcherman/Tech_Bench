<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;

use App\Customers;

class GetCustomerDetails
{
    //  Pull the details of a customer
    public function getDetails($custID)
    {
        $details = Customers::find($custID);
        Log::debug('Customer Details for Customer ID '.$custID.' gathered.  Data - ', $details != null ? $details->toArray() : []);
        return $details;
    }

    //  Determine if the customer belongs to a parent customer
    public function getParentID($custID)
    {
        return Customers::findOrFail($custID)->parent_id;
    }
}
