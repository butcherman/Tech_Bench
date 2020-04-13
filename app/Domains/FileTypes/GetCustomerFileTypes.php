<?php

namespace App\Domains\FileTypes;

use Illuminate\Support\Facades\Log;

use App\CustomerFileTypes;

use App\Http\Resources\CustomerFileTypesCollection;

class GetCustomerFileTypes
{
    //  Retrieve the types of files that can be assigned to a customer
    public function execute($collection = false)
    {
        $fileTypes = CustomerFileTypes::all();
        Log::debug('Retrieved list of file types available to assign to customer.  Data - ', array($fileTypes));
        if($collection)
        {
            return new CustomerFileTypesCollection($fileTypes);
        }

        return $fileTypes;
    }
}
