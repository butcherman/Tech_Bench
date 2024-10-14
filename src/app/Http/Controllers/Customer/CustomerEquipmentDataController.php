<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentDataRequest;
use App\Models\Customer;
use App\Service\Customer\CustomerEquipmentDataService;
use Illuminate\Http\RedirectResponse;

class CustomerEquipmentDataController extends Controller
{
    public function __construct(protected CustomerEquipmentDataService $svc) {}

    /**
     * Save changes made to Customer Equipment Data
     */
    public function __invoke(CustomerEquipmentDataRequest $request, Customer $customer): RedirectResponse
    {
        $this->svc->updateEquipmentDataFields($request, $customer);

        return back()->with('success', 'Saved Successfully');
    }
}
