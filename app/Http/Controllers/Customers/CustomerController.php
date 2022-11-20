<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\EquipmentOptionList;
use App\Http\Requests\Customers\CustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Search page for customers
     */
    public function index(Request $request)
    {
        return Inertia::render('Customers/Index', [
            'per-page'       => 25,
            'pagination-arr' => [25, 50, 100],
            'permissions'    => ['create' => $request->user()->can('create', Customer::class)],
            'equipment'      => (new EquipmentOptionList)->build(),
        ]);
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customers/Create', [
            'select-id'     => config('customer.select_id'),
            'default-state' => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created Customer
     */
    public function store(CustomerRequest $request)
    {
        $request->setSlug();
        $newCust = Customer::create($request->only([
            'cust_id', 'parent_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip', 'slug'
        ]));

        Log::stack(['daily', 'cust', 'user'])->info('New Customer create by '.$request->user()->username, $newCust->toArray());
        return redirect(route('customers.show', $newCust->slug));
    }

    /**
     * Display the specified Customer
     */
    public function show($id)
    {
        return Inertia::render('Customers/Show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return 'destroy';
    }
}
