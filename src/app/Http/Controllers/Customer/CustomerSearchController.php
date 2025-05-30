<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use App\Service\Customer\CustomerSearchService;
use Illuminate\Http\JsonResponse;

class CustomerSearchController extends Controller
{
    /**
     * Perform a search for a customer
     */
    public function __invoke(CustomerSearchRequest $request): JsonResponse
    {
        $searchObj = new CustomerSearchService($request);

        return response()->json($searchObj());
    }
}
