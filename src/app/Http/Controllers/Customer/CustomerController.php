<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Customer/Index', [
            'permissions' => BuildCustomerPermissions::build($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customer/Create', [
            'selectId' => (bool) config('customer.select_id'),
            'default-state' => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $newCustomer = $request->createNewCustomer();

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
        return Inertia::render('Customer/Show', [
            'permissions' => BuildCustomerPermissions::build($request->user()),
            'customer' => $customer,
        ]);
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
