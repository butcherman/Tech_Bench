<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSearchRequest;

class CustomerSearchController extends Controller
{
    /**
     * Search for a customer
     */
    public function __invoke(CustomerSearchRequest $request)
    {
        return $request->checkSearch();
    }
}
