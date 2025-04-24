<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSiteRequest;
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
            'alerts' => fn() => $customer->CustomerAlert,
            'customer' => fn() => $customer,
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'siteList' => fn() => $customer->CustomerSite->makeVisible(['href']),
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

    public function show(Customer $customer, CustomerSite $site): Response
    {
        return Inertia::render('Customer/Site/Show');
    }

    public function edit(string $id)
    {
        //
        return 'edit';
    }

    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    public function restore(string $id)
    {
        //
        return 'restore';
    }

    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
