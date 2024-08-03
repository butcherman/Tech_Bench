<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use App\Service\CustomerSearchService;
use App\Service\TechTipSearchService;
use Illuminate\Http\JsonResponse;

class CustomerSearchController extends Controller
{
    /**
     * Perform a search for a customer
     */
    public function __invoke(CustomerSearchRequest $request)
    {
        $searchObj = new CustomerSearchService($request);

        return $searchObj->search();
    }
}
