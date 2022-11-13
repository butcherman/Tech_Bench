<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSearchRequest;
use Illuminate\Http\Request;

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
