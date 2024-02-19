<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer = null)
    {
        return Inertia::render('Customer/Site/Create', [
            'default-state' => config('customer.default_state'),
            'parent-customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerSiteRequest $request, Customer $customer = null)
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
            $newSite->site_slug
        ]))->with('success', __('cust.site.created', ['name' => $newSite->site_name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show customer site';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
