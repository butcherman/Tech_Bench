<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerVpn;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareVpnDataController extends Controller
{
    /**
     * Share VPN Data with another Customer Profile
     */
    public function __invoke(
        CustomerEquipmentService $svc,
        Customer $customer,
        CustomerVpn $vpn_datum
    ): RedirectResponse {
        $svc->shareCustomerVpnData($vpn_datum, $customer);

        return back()->with('success', 'VPN Data Shared');
    }
}
