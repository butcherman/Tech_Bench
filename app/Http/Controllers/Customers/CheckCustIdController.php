<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CheckCustIdController extends Controller
{
    /**
     *  Check to see if a customer ID exists or not
     */
    public function __invoke(Request $request)
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
