<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LinkCustomerController extends Controller
{
    /**
     *  Link a child customer to its parent site
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage', Customer::class);

        //  Verify that the selected parent ID does not have a parent ID
        $parent = Customer::findOrFail($request->parent_id);
        if($parent->parent_id)
        {
            $request->parent_id = $parent->parent_id;
        }

        Customer::find($request->cust_id)->update([
            'parent_id' => $request->parent_id,
        ]);
        Log::channel('cust')->info('Customer ID '.$request->cust_id.' has been linked to '.$request->parent_id.' by '.$request->user()->username);

        return back()->with(['message' => 'Customer successfully linked', 'type' => 'success']);
    }
}
