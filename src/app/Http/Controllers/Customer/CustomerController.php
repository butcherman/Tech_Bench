<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\DisableCustomerRequest;
use App\Jobs\Customer\ForceDeleteCustomerJob;
use App\Models\Customer;
use App\Services\Customer\CustomerAdministrationService;
use App\Services\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Search page for finding a customer profile.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/Index', [
            'permissions' => UserPermissions::customerPermissions($request->user()),
        ]);
    }

    /**
     * Create new customer profile form.
     */
    public function create(): Response
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customer/Create', [
            'select-id' => fn() => config('customer.select_id'),
            'default-state' => fn() => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request, CustomerService $svc): RedirectResponse
    {
        $newCustomer = $svc->createCustomer($request->safe()->collect());

        return redirect(route('customers.show', $newCustomer->slug))
            ->with('success', __('cust.created', [
                'name' => $newCustomer->name,
            ]));
    }

    /**
     * Show a customer profile.
     */
    public function show(Request $request, Customer $customer): Response
    {
        $customer->touchRecent($request->user());

        if ($customer->site_count > 1) {
            return Inertia::render('Customer/Show', [
                'customer' => fn() => $customer,
                'isFav' => fn() => $customer->isFav($request->user()),
                'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            ]);
        }

        return Inertia::render('Customer/Site/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): Response
    {
        $this->authorize('update', $customer);

        return Inertia::render('Customer/Edit', [
            'selectId' => fn() => config('customer.select_id'),
            'default-state' => fn() => config('customer.default_state'),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CustomerRequest $request,
        CustomerService $svc,
        Customer $customer
    ): RedirectResponse {
        $updated = $svc->updateCustomer($request->safe()->collect(), $customer);

        return redirect(route('customers.show', $updated->slug))
            ->with('success', __('cust.updated', ['name' => $updated->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DisableCustomerRequest $request,
        CustomerService $svc,
        Customer $customer
    ): RedirectResponse {
        $svc->destroyCustomer($customer, $request->get('reason'));

        return redirect(route('customers.index'))
            ->with('danger', __('cust.destroy', ['name' => $customer->name]));
    }

    /**
     * Restore a soft deleted customer.
     */
    public function restore(CustomerService $svc, Customer $customer): RedirectResponse
    {
        $this->authorize('restore', $customer);

        $svc->restoreCustomer($customer);

        Log::notice('Customer ' . $customer->name . ' has been restored');

        return back()
            ->with('success', __('cust.restored', ['name' => $customer->name]));
    }

    /**
     * Force Delete a soft deleted customer.
     */
    public function forceDelete(
        CustomerAdministrationService $svc,
        Customer $customer
    ): RedirectResponse {
        $this->authorize('forceDelete', $customer);

        // Place customer in working-jobs cache so it is not accessible.
        $svc->addToWorkingJobs($customer);

        ForceDeleteCustomerJob::dispatch($customer);

        return back()->with('danger', __('cust.force_deleted', [
            'name' => $customer->name,
        ]));
    }
}
