<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerAlertRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerAdministrationService;
use Inertia\Inertia;

class CustomerAlertController extends Controller
{
    public function __construct(protected CustomerAdministrationService $svc) {}

    /**
     * Display a listing of alerts for the requested customer
     */
    // public function index(Customer $customer): Response
    // {
    //     $this->authorize('viewAny', CustomerAlert::class);

    //     return Inertia::render('Customer/Alert/Index', [
    //         'customer' => fn () => $customer,
    //         'alerts' => fn () => $customer->CustomerAlert,
    //     ]);
    // }

    /**
     * Store a newly created customer alert
     */
    // public function store(CustomerAlertRequest $request, Customer $customer): RedirectResponse
    // {
    //     $this->svc->createCustomerAlert($request, $customer);

    //     return back()->with('success', __('cust.alert.created'));
    // }

    /**
     * Update the specified customer alert
     */
    // public function update(
    //     CustomerAlertRequest $request,
    //     Customer $customer,
    //     CustomerAlert $alert
    // ): RedirectResponse {
    //     $this->svc->updateCustomerAlert(
    //         $request,
    //         $customer,
    //         $alert
    //     );

    //     return back()->with('success', __('cust.alert.updated'));
    // }

    /**
     * Remove the specified customer alert
     */
    // public function destroy(Customer $customer, CustomerAlert $alert): RedirectResponse
    // {
    //     $this->authorize('delete', $alert);

    //     $this->svc->removeCustomerAlert($customer, $alert);

    //     return back()->with('warning', __('cust.alert.destroy'));
    // }
}
