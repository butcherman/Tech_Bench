<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Customers;


class GetCustomerDetails
{
    protected function getParentID($custID)
    {
        return Customers::findOrFail($custID)->parent_id;
    }
}
