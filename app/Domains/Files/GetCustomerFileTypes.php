<?php

namespace App\Domains\Files;

use App\CustomerFileTypes;

use Illuminate\Support\Facades\Log;

class GetCustomerFileTypes
{
    //  Get the types of files that can be uploaded for a customer
    public function execute()
    {
        return CustomerFileTypes::all();
    }
}
