<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerVpnRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerVpn;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;

class CustomerVpnController extends Controller
{
    public function __construct(protected CustomerEquipmentService $svc)
    {
        if (! config('customer.allow_vpn_data')) {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerVpnRequest $request, Customer $customer)
    {
        $this->svc->createCustomerVpnData($request->safe()->collect(), $customer);

        return back()->with('success', 'VPN Data Saved');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerVpnRequest $request, Customer $customer, CustomerVpn $vpn_datum)
    {
        $this->svc->updateCustomerVpnData($request->safe()->collect(), $vpn_datum);

        return back()->with('success', 'VPN Data Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, CustomerVpn $vpn_datum): RedirectResponse
    {
        $this->authorize('delete', CustomerEquipment::class);

        $this->svc->destroyCustomerVpnData($vpn_datum, $customer);

        return back()->with('warning', 'VPN Data Deleted');
    }
}
