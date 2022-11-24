<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class GetLinkedCustomerController extends Controller
{
    /**
     * Get a list of all child customers for the parent customer
     */
    public function __invoke(Customer $customer)
    {
        return Customer::where('parent_id', $customer->cust_id)->get();
    }
}
