<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CheckIdRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CheckIdController extends Controller
{
    /**
     * Check to see if the supplied Customer ID is already in use
     */
    public function __invoke(CheckIdRequest $request)
    {
        $cust = Customer::find($request->cust_id);

        if($cust)
        {
            return response()->json([
                'valid' => false,
                'data'  => [
                    'message' => 'Customer ID is taken by '.$cust->name,
                ]], 200);
        }

        return response()->json([
            'valid' => true,
            'data'  => [
                'message' => 'Customer ID is available',
            ]], 200);
    }
}
