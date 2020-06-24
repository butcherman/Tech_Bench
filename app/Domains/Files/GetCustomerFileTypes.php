<?php

namespace App\Domains\Files;

use App\CustomerFileTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetCustomerFileTypes
{
    public function execute()
    {
        return CustomerFileTypes::all();
    }
}
