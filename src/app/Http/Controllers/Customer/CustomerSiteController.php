<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDisableRequest;
use App\Http\Requests\Customer\CustomerSiteRequest;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Customer $customer)
    {
        return Inertia::render('Customer/Site/Index', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'alerts' => fn() => $customer->CustomerAlert,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?Customer $customer = null)
    {
        $this->authorize('create', CustomerSite::class);

        return Inertia::render('Customer/Site/Create', [
            'default-state' => fn() => config('customer.default_state'),
            'parent-customer' => fn() => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerSiteRequest $request, ?Customer $customer = null)
    {
        if ($customer === null) {
            $customer = Customer::find($request->cust_id);
        }

        $request->setSlug();
        $newSite = CustomerSite::create($request->except(['cust_name']));

        Log::channel('cust')->info('New Customer Site created for ' . $request->cust_name .
            ' by ' . $request->user()->username, $newSite->toArray());

        return redirect(route('customers.sites.show', [
            $newSite->Customer->slug,
            $newSite->site_slug,
        ]))->with('success', __('cust.site.created', ['name' => $newSite->site_name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerSite $site)
    {
        return Inertia::render('Customer/Site/Show', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'site' => fn() => $site,
            'siteList' => fn() => $customer->CustomerSite,
            'alerts' => fn() => $customer->CustomerAlert,
            'equipmentList' => fn() => $site->SiteEquipment,
            'contacts' => fn() => $customer->CustomerContact,
            'notes' => fn() => $site->getNotes(),
            'files' => fn() => $site->getFiles()->append('href'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer, CustomerSite $site)
    {
        $this->authorize('update', $site);

        return Inertia::render('Customer/Site/Edit', [
            'default-state' => fn() => config('customer.default_state'),
            'parent-customer' => fn() => $customer,
            'site' => fn() => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerSiteRequest $request, Customer $customer, CustomerSite $site)
    {
        $request->setSlug();
        $site->update($request->except(['cust_name']));

        Log::channel('cust')->info('Customer Site ' . $site->site_name . ' updated for ' .
            $request->cust_name . ' by ' . $request->user()->username, $site->toArray());

        return redirect(route('customers.sites.show', [
            $customer->slug,
            $site->site_slug,
        ]))->with('success', __('cust.site.updated', ['name' => $site->site_name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerDisableRequest $request, Customer $customer, CustomerSite $site)
    {
        $site->update(['deleted_reason' => $request->reason]);
        $site->delete();

        Log::channel('cust')->alert('Customer Site ' . $site->site_name . ' for ' .
            $customer->name . ' has been disabled by ' . $request->user()->username);

        return redirect(route('customers.show', $customer->slug))->with('danger', __('cust.destroy', [
            'name' => $site->site_name,
        ]));
    }
}
