<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\LinkedCustomerRequest;
use App\Models\Customer;

class LinkedCustomerController extends Controller
{
    /**
     * Get a list of all child customers for the parent customer
     */
    public function get(Customer $customer)
    {
        return Customer::where('parent_id', $customer->cust_id)->get();
    }

    public function set(LinkedCustomerRequest $request)
    {
        $msg = $request->processLink();

        return back()->with($msg['type'], $msg['message']);
    }
}
