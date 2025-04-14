<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerSearchController extends Controller
{
    /**
     * Search for a customer based on ID, Customer Name or Site Name.
     */
    public function __invoke(CustomerSearchRequest $request, CustomerSearch $svc): mixed
    {
        return $svc($request->safe()->collect());
    }
}
