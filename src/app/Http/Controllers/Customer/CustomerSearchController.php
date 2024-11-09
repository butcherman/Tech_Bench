<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use Illuminate\Http\Request;

class CustomerSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        CustomerSearch $search,
        CustomerSearchRequest $request
    ): mixed {
        return $search($request->safe()->collect());
    }
}
