<?php

namespace App\Domains\FileTypes;

use App\CustomerFileTypes;
use App\Http\Resources\CustomerFileTypesCollection;

class GetCustomerFileTypes
{
    //  Retrieve the types of files that can be assigned to a customer
    public function execute($collection = false)
    {
        if($collection)
        {
            return new CustomerFileTypesCollection(CustomerFileTypes::all());
        }

        return CustomerFileTypes::all();
    }
}
