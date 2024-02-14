<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use Illuminate\Http\Request;

class CustomerSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CustomerSearchRequest $request)
    {
        return response()->json($request->search());
    }
}
