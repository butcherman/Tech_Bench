<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEquipmentDataEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;

class CustomerEquipmentDataController extends Controller
{
    /**
     * Save changes made to Customer Equipment Data
     */
    public function __invoke(CustomerEquipmentDataRequest $request, Customer $customer): RedirectResponse
    {
        $request->processDataChanges();

        event(new CustomerEquipmentDataEvent($customer));

        return back()->with('success', 'Saved Successfully');
    }
}
