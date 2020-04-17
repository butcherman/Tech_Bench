<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\CustomerFavs;

class DestroyCustomer
{
    public function softDelete($custID)
    {
        //  Remove the customer from any users favorites
        CustomerFavs::where('cust_id', $custID)->delete();
        //  Disable the customer
        Customers::destroy($custID);

        Log::notice('User - '.Auth::user()->full_name.' has deactivated Customer ID '.$custID);
        return true;
    }
}
