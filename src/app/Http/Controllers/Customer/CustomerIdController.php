<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CustomerIdController extends Controller
{
    /**
     * Determine if a customer ID Number is already in use
     */
    public function __invoke(CustomerSearch $svc, int $custId): JsonResponse
    {
        $cust = $svc(collect(['cust_id' => $custId]));

        return response()->json([
            'in_use' => $cust ? true : false,
            'cust_name' => $cust ? $cust->name : null,
            'route' => $cust ? route('customers.show', $cust->slug) : null,
        ]);
    }
}
