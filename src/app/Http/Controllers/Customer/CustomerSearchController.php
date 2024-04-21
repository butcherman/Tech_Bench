<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use Illuminate\Http\JsonResponse;

class CustomerSearchController extends Controller
{
    /**
     * Perform a search for a customer
     */
    public function __invoke(CustomerSearchRequest $request): JsonResponse
    {
        return response()->json($request->search());
    }
}
