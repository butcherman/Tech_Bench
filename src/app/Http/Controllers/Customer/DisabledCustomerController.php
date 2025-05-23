<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\Customer\CustomerAdministrationService;
use Inertia\Inertia;
use Inertia\Response;

class DisabledCustomerController extends Controller
{
    /**
     * Get a list of customers that have been soft deleted
     */
    public function __invoke(CustomerAdministrationService $svc): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/Admin/DisabledCustomers', [
            'disabled-list' => $svc->getDisabledCustomers(),
        ]);
    }
}
