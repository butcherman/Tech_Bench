<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BreakCustomerLinkController extends Controller
{
    /**
     *  Break the link between the customer and its parent
     */
    public function __invoke($cust_id)
    {
        $this->authorize('manage', Customer::class);
        Customer::findOrFail($cust_id)->update(['parent_id' => null]);

        Log::channel('cust')->notice('Customer Link broken for Customer ID '.$cust_id.' by '.Auth::user()->username);
        return back()->with(['message' => 'Customer Link Broken', 'type' => 'warning']);
    }
}
