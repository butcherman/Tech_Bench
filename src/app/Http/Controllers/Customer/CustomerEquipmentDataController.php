<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEquipmentDataFieldChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Http\RedirectResponse;

class CustomerEquipmentDataController extends Controller
{
    public function __construct(protected CustomerEquipmentDataService $svc) {}

    /**
     * Save changes made to Customer Equipment Data
     */
    public function __invoke(
        CustomerEquipmentDataRequest $request,
        Customer $customer
    ): RedirectResponse {
        $this->svc
            ->updateDataFieldValue($request->safe()->collect(), $customer);

        event(new CustomerEquipmentDataFieldChanged($customer));

        return back()->with('success', 'Saved Successfully');
    }
}
