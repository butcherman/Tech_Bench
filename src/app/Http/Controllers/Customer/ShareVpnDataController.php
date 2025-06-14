<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerVpn;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;

class ShareVpnDataController extends Controller
{
    public function __construct()
    {
        if (
            ! config('customer.allow_share_vpn_data')
            || ! config('customer.allow_vpn_data')
        ) {
            abort(403);
        }
    }

    /**
     * Share VPN Data with another Customer Profile
     */
    public function __invoke(
        CustomerEquipmentService $svc,
        Customer $customer,
        CustomerVpn $vpn_datum
    ): RedirectResponse {
        $this->authorize('manage', $customer);

        $svc->shareCustomerVpnData($vpn_datum, $customer);

        return back()->with('success', 'VPN Data Shared');
    }
}
