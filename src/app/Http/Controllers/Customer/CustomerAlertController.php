<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerAlertRequest;
use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Services\Customer\CustomerAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerAlertController extends Controller
{
    public function __construct(protected CustomerAlertService $svc) {}

    /**
     * Show a list of Customer Alerts for the selected Customer.
     */
    public function index(Customer $customer): Response
    {
        $this->authorize('viewAny', CustomerAlert::class);

        return Inertia::render('Customer/Alert/Index', [
            'customer' => fn() => $customer,
            'alerts' => fn() => $customer->CustomerAlert,
        ]);
    }

    /**
     * Store a newly created alert.
     */
    public function store(CustomerAlertRequest $request, Customer $customer): RedirectResponse
    {
        $this->svc->createCustomerAlert($request->safe()->collect(), $customer);

        return back()->with('success', __('cust.alert.created'));
    }

    /**
     * Update an existing alert.
     */
    public function update(
        CustomerAlertRequest $request,
        Customer $customer,
        CustomerAlert $alert
    ): RedirectResponse {
        $this->svc->updateCustomerAlert($request->safe()->collect(), $alert);

        return back()->with('success', __('cust.alert.updated'));
    }

    /**
     *
     */
    public function destroy(Customer $customer, CustomerAlert $alert): RedirectResponse
    {
        $this->authorize('delete', $alert);

        $this->svc->destroyCustomerAlert($alert);

        return back()->with('warning', __('cust.alert.destroy'));
    }
}
