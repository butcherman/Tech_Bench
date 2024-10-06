<?php

// TODO - Refactor

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerIdController extends Controller
{
    /**
     * Determine if a customer ID Number is already in use
     */
    public function __invoke(int $custId): JsonResponse
    {
        $cust = Customer::find($custId);

        return response()->json([
            'in_use' => $cust ? true : false,
            'cust_name' => $cust ? $cust->name : null,
            'route' => $cust ? route('customers.show', $cust->slug) : null,
        ]);
    }
}
