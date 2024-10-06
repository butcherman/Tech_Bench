<?php

// TODO - Refactor

namespace App\Http\Controllers\Customer;

use App\Actions\CustomerPermissions;
use App\Enum\CrudAction;
use App\Events\Customer\CustomerSiteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDisableRequest;
use App\Http\Requests\Customer\CustomerSiteRequest;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerSiteController extends Controller
{
    public function __construct(protected CustomerPermissions $permissions) {}

    /**
     * Display a listing of the Customer Sites.
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Site/Index', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'alerts' => fn () => $customer->CustomerAlert,
        ]);
    }

    /**
     * Show the form for creating a new Customer Site.
     */
    public function create(?Customer $customer = null): Response
    {
        $this->authorize('create', CustomerSite::class);

        return Inertia::render('Customer/Site/Create', [
            'default-state' => fn () => config('customer.default_state'),
            'parent-customer' => fn () => $customer,
        ]);
    }

    /**
     * Store a newly created Customer Site in storage.
     */
    public function store(CustomerSiteRequest $request, ?Customer $customer = null): RedirectResponse
    {
        // If the customer was not included in the URL, find based on request data
        if ($customer === null) {
            $customer = Customer::find($request->cust_id);
        }

        $request->setSlug();
        $newSite = CustomerSite::create($request->except(['cust_name']));

        Log::info('New Customer Site created for '.$request->cust_name.
            ' by '.$request->user()->username, $newSite->toArray());

        event(new CustomerSiteEvent($customer, $newSite, CrudAction::Create));

        return redirect(route('customers.sites.show', [
            $newSite->Customer->slug,
            $newSite->site_slug,
        ]))->with('success', __('cust.site.created', [
            'name' => $newSite->site_name,
        ]));
    }

    /**
     * Display the specified Customer Site.
     */
    public function show(Request $request, Customer $customer, CustomerSite $site): Response
    {
        // Update the users recent activity
        $customer->touchRecent($request->user());

        return Inertia::render('Customer/Site/Show', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'site' => fn () => $site,
            'siteList' => fn () => $customer->CustomerSite,
            'alerts' => fn () => $customer->CustomerAlert,
            'equipmentList' => fn () => $site->SiteEquipment,
            'contacts' => fn () => $customer->CustomerContact,
            'notes' => fn () => $site->getNotes(),
            'files' => fn () => $site->getFiles()->append('href'),
            'is-fav' => fn () => $customer->isFav($request->user()),
        ]);
    }

    /**
     * Show the form for editing the specified Customer Site.
     */
    public function edit(Customer $customer, CustomerSite $site): Response
    {
        $this->authorize('update', $site);

        return Inertia::render('Customer/Site/Edit', [
            'default-state' => fn () => config('customer.default_state'),
            'parent-customer' => fn () => $customer,
            'site' => fn () => $site,
        ]);
    }

    /**
     * Update the specified Customer Site in storage.
     */
    public function update(CustomerSiteRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $request->setSlug();
        $site->update($request->except(['cust_name']));

        Log::info('Customer Site '.$site->site_name.' updated for '.
            $request->cust_name.' by '.$request->user()->username, $site->toArray());

        event(new CustomerSiteEvent($customer, $site, CrudAction::Update));

        return redirect(route('customers.sites.show', [
            $customer->slug,
            $site->site_slug,
        ]))->with('success', __('cust.site.updated', ['name' => $site->site_name]));
    }

    /**
     * Remove the specified Customer Site from storage.
     */
    public function destroy(CustomerDisableRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $site->update(['deleted_reason' => $request->reason]);
        $site->delete();

        Log::alert('Customer Site '.$site->site_name.' for '.
            $customer->name.' has been disabled by '.$request->user()->username);

        event(new CustomerSiteEvent($customer, $site, CrudAction::Destroy));

        return redirect(route('customers.show', $customer->slug))
            ->with('danger', __('cust.destroy', [
                'name' => $site->site_name,
            ]));
    }
}
