<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDisableRequest;
use App\Http\Requests\Customer\CustomerSiteRequest;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Service\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerSiteController extends Controller
{
    public function __construct(
        protected CustomerPermissions $permissions,
        protected CustomerService $svc
    ) {}

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
        $newSite = $this->svc->createSite($request, $customer);

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
    public function update(
        CustomerSiteRequest $request,
        Customer $customer,
        CustomerSite $site
    ): RedirectResponse {
        $this->svc->updateSite($request, $site);

        return redirect(route('customers.sites.show', [
            $customer->slug,
            $site->site_slug,
        ]))->with('success', __('cust.site.updated', [
            'name' => $site->site_name,
        ]));
    }

    /**
     * Remove the specified Customer Site from storage.
     */
    public function destroy(CustomerDisableRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $this->svc->destroySite($site, $request->reason);

        return redirect(route('customers.show', $customer->slug))
            ->with('danger', __('cust.destroy', [
                'name' => $site->site_name,
            ]));
    }
}
