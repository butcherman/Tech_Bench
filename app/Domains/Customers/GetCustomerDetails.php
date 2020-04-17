<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\Http\Resources\Customers as CustomerResource;

class GetCustomerDetails
{
    public function getDetailsWithParent($custID, $collection = false)
    {
        $details = Customers::where('cust_id', $custID)->with('ParentCustomer')->first();

        if($collection)
        {
            return new CustomerResource($details);
        }
        return $details;
    }
}
