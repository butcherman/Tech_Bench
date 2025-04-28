<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerEquipmentDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        CustomerEquipmentDataRequest $request,
        CustomerEquipmentDataService $svc,
        Customer $customer,
        CustomerEquipment $equipment
    ): RedirectResponse {
        $svc->updateDataFieldValue($request->safe()->collect(), $equipment);

        return back()->with('success', 'Saved Successfully');
    }
}
