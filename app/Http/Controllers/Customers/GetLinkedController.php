<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class GetLinkedController extends Controller
{
    /**
     * Get all customers that are linked to a specific parent customer
     */
    public function __invoke($cust_id)
    {
        return Customer::where('parent_id', $cust_id)->get();
    }
}
