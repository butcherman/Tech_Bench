<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerEquipmentDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CustomerEquipmentDataRequest $request, Customer $customer)
    {
        $request->processDataChanges();

        return back()->with('success', 'changes saved');
    }
}
