<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CheckCustIdController extends Controller
{
    /**
     * Ajax request to see if a customer ID is in use or not
     */
    public function __invoke($custId)
    {
        $cust = Customer::find($custId);

        if ($cust) {
            return response()->json([
                'valid' => false,
                'name' => $cust->name,
            ]);
        }

        return response()->json([
            'valid' => true,
        ]);
    }
}
