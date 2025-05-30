<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDisableRequest;
use App\Http\Requests\Customer\CustomerRequest;
use App\Jobs\Customer\DestroyCustomerJob;
use App\Models\Customer;
use App\Service\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerPermissions $permissions,
        protected CustomerService $svc
    ) {}

    /**
     * Customer Search Page
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/Index', [
            'permissions' => fn () => $this->permissions->get($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new Customer.
     */
    public function create(): Response
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customer/Create', [
            'selectId' => fn () => (bool) config('customer.select_id'),
            'default-state' => fn () => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created Customer in storage.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        $newCustomer = $this->svc->createCustomer($request);

        return redirect(route('customers.show', $newCustomer->slug))
            ->with('success', __('cust.created', [
                'name' => $newCustomer->name,
            ]));
    }

    /**
     * Display the specified Customer.
     */
    public function show(Request $request, Customer $customer): Response
    {
        // Update the users recent activity
        $customer->touchRecent($request->user());

        // If the customer has multiple sites, show the Customer Home Page
        if ($customer->CustomerSite->count() > 1 || $customer->CustomerSite->count() == 0) {
            return Inertia::render('Customer/Show', [
                'permissions' => fn () => $this->permissions->get($request->user()),
                'customer' => fn () => $customer,
                'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
                'alerts' => fn () => $customer->CustomerAlert,
                'equipmentList' => fn () => $customer->CustomerEquipment,
                'contacts' => fn () => $customer->CustomerContact,
                'notes' => fn () => $customer->CustomerNote,
                'files' => fn () => $customer->CustomerFile->append('href'),
                'is-fav' => fn () => $customer->isFav($request->user()),
            ]);
        }

        // If the customer only has a single site, show that sites details
        return Inertia::render('Customer/Site/Show', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'site' => fn () => $customer->CustomerSite[0],
            'siteList' => fn () => $customer->CustomerSite,
            'alerts' => fn () => $customer->CustomerAlert,
            'equipmentList' => fn () => $customer->CustomerEquipment,
            'contacts' => fn () => $customer->CustomerContact,
            'notes' => fn () => $customer->CustomerNote,
            'files' => fn () => $customer->CustomerFile->append('href'),
            'is-fav' => fn () => $customer->isFav($request->user()),
        ]);
    }

    /**
     * Show the form for editing the specified Customer.
     */
    public function edit(Customer $customer): Response
    {
        $this->authorize('update', $customer);

        return Inertia::render('Customer/Edit', [
            'selectId' => fn () => (bool) config('customer.select_id'),
            'default-state' => fn () => config('customer.default_state'),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite,
        ]);
    }

    /**
     * Update the specified Customer in storage.
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $updatedCustomer = $this->svc->updateCustomer($request, $customer);

        return redirect(route('customers.show', $updatedCustomer->slug))
            ->with('success', __('cust.updated', [
                'name' => $updatedCustomer->name,
            ]));
    }

    /**
     * Remove the specified Customer from storage.
     */
    public function destroy(CustomerDisableRequest $request, Customer $customer): RedirectResponse
    {
        $this->svc->destroyCustomer($customer, $request->reason);

        return redirect(route('customers.index'))->with('danger', __('cust.destroy', [
            'name' => $customer->name,
        ]));
    }

    /**
     * Restore a soft deleted customer
     */
    public function restore(Customer $customer): RedirectResponse
    {
        $this->authorize('restore', $customer);

        $this->svc->restoreCustomer($customer);

        return back()
            ->with('success', __('cust.restored', ['name' => $customer->name]));
    }

    /**
     * Completely remove a soft deleted customer
     */
    public function forceDelete(Customer $customer): RedirectResponse
    {
        $this->authorize('forceDelete', $customer);

        dispatch(new DestroyCustomerJob($customer));

        return back()->with('danger', __('cust.force_deleted', [
            'name' => $customer->name,
        ]));
    }
}
