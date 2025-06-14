<?php

namespace App\Http\Controllers\Customer;

use App\Facades\CacheData;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSiteRequest;
use App\Http\Requests\Customer\DisableCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Services\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerSiteController extends Controller
{
    public function __construct(protected CustomerService $svc) {}

    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Site/Index', [
            'alerts' => fn() => $customer->Alerts,
            'customer' => fn() => $customer,
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),
            'isFav' => fn() => $customer->isFav($request->user()),
        ]);
    }

    /**
     * Form for creating a new Customer Site.
     */
    public function create(?Customer $customer = null): Response
    {
        $this->authorize('create', CustomerSite::class);

        return Inertia::render('Customer/Site/Create', [
            'default-state' => fn() => config('customer.default_state'),
            'parent-customer' => fn() => $customer,
        ]);
    }

    /**
     * Save a new Customer Site.
     */
    public function store(CustomerSiteRequest $request, ?Customer $customer = null): RedirectResponse
    {
        $newSite = $this->svc->createSite($request->safe()->collect(), $customer);

        return redirect(route('customers.sites.show', [
            $newSite->Customer->slug,
            $newSite->site_slug,
        ]))->with('success', __('cust.site.created', [
            'name' => $newSite->site_name,
        ]));
    }

    public function show(Request $request, Customer $customer, CustomerSite $site): Response
    {
        return Inertia::render('Customer/Site/Show', [
            'alerts' => fn() => $customer->Alerts,
            'allowShareVpn' => fn() => config('customer.allow_share_vpn_data'),
            'allowVpn' => fn() => config('customer.allow_vpn_data'),
            'availableEquipment' => fn() => CacheData::equipmentCategorySelectBox(),
            'customer' => fn() => $customer,
            'currentSite' => fn() => $site,
            'isFav' => fn() => $customer->isFav($request->user()),
            'permissions' => fn() => UserPermissions::customerPermissions(
                $request->user()
            ),
            'phoneTypes' => fn() => CacheData::phoneTypes(),
            'fileTypes' => fn() => CacheData::fileTypes(),

            /**
             * Deferred Props
             */
            'contactList' => Inertia::defer(fn() => $site->SiteContact),
            'groupedEquipmentList' => Inertia::defer(
                fn() => $site->SiteEquipment
                    ->load('Sites')
                    ->groupBy('equip_name')
                    ->chunk(5)
            ),
            'equipmentList' => Inertia::defer(fn() => $site->SiteEquipment->load('Sites')),
            'noteList' => Inertia::defer(fn() => $site->SiteNote),
            'fileList' => Inertia::defer(fn() => $site->SiteFile->append('href')),
            'vpnData' => Inertia::defer(fn() => $customer->CustomerVpn),
        ]);
    }

    public function edit(Customer $customer, CustomerSite $site): Response
    {
        $this->authorize('update', $site);

        return Inertia::render('Customer/Site/Edit', [
            'default-state' => fn() => config('customer.default_state'),
            'parent-customer' => fn() => $customer,
            'site' => fn() => $site,
        ]);
    }

    public function update(CustomerSiteRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $this->svc->updateSite($request->safe()->collect(), $site);

        return redirect(route('customers.sites.show', [
            $customer->slug,
            $site->site_slug,
        ]))->with('success', __('cust.site.updated', [
            'name' => $site->site_name,
        ]));
    }

    public function destroy(DisableCustomerRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $this->svc->destroySite($site, $request->get('reason'));

        return redirect(route('customers.show', $customer->slug))
            ->with('danger', __('cust.destroy', ['name' => $site->site_name]));
    }

    public function restore(Customer $customer, CustomerSite $site): RedirectResponse
    {
        $this->authorize('restore', $customer);

        $this->svc->restoreSite($site);

        return back()->with('success', 'Customer Site Restored');
    }

    public function forceDelete(Customer $customer, CustomerSite $site): RedirectResponse
    {
        $this->authorize('forceDelete', $customer);

        $this->svc->destroySite($site, 'Deleting Site', true);

        return back()->with('danger', 'Customer Site Deleted');
    }
}
