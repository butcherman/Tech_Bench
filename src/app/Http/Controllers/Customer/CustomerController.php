<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDisableRequest;
use App\Http\Requests\Customer\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Customer/Index', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customer/Create', [
            'selectId' => fn() => (bool) config('customer.select_id'),
            'default-state' => fn() => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $newCustomer = $request->createNewCustomer();

        Log::channel('cust')
            ->info('New Customer ' . $newCustomer->name . ' created by '
                . $request->user()->username, $newCustomer->toArray());

        return redirect(route('customers.show', $newCustomer->slug))
            ->with('success', __('cust.created', [
                'name' => $newCustomer->name,
            ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer)
    {

        if ($customer->CustomerSite->count() > 1 || $customer->CustomerSite->count() == 0) {
            return Inertia::render('Customer/Show', [
                'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
                'customer' => fn() => $customer,
                'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
                'alerts' => fn() => $customer->CustomerAlert,
                'equipmentList' => fn() => $customer->CustomerEquipment,
                'contacts' => fn() => $customer->CustomerContact,
                'notes' => fn() => $customer->CustomerNote,
            ]);
        }

        return Inertia::render('Customer/Site/Show', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'site' => fn() => $customer->CustomerSite[0],
            'siteList' => fn() => $customer->CustomerSite,
            'alerts' => fn() => $customer->CustomerAlert,
            'equipmentList' => fn() => $customer->CustomerEquipment,
            'contacts' => fn() => $customer->CustomerContact,
            'notes' => fn() => $customer->CustomerNote,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        return Inertia::render('Customer/Edit', [
            'selectId' => fn() => (bool) config('customer.select_id'),
            'default-state' => fn() => config('customer.default_state'),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $updatedCustomer = $request->updateCustomer($customer);

        Log::channel('cust')
            ->info('Customer information updated for ' . $customer->name
                . ' by ' . $request->user()->username, $customer->toArray());

        return redirect(route('customers.show', $updatedCustomer->slug))
            ->with('success', __('cust.updated', [
                'name' => $updatedCustomer->name,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerDisableRequest $request, Customer $customer)
    {
        $customer->update(['deleted_reason' => $request->reason]);
        $customer->delete();

        Log::channel('cust')->alert('Customer ' . $customer->name . ' has been disabled by ' .
            $request->user()->username);

        return redirect(route('customers.index'))->with('danger', __('cust.destroy', [
            'name' => $customer->name,
        ]));
    }
}
