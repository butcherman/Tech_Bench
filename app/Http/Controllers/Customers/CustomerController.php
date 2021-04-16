<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;

use App\Models\Customer;
use App\Models\EquipmentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerRequest;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     *  Customer search page
     */
    public function index(Request $request)
    {
        return Inertia::render('Customer/index', [
            'can_create'  => $request->user()->can('create', Customer::class),
            'equip_types' => EquipmentType::orderBy('cat_id')->get()->pluck('name')->values(),
        ]);
    }

    /**
     *  Form to create a new customer
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
        return Inertia::render('Customer/create');
    }

    /**
     *  Save a newly created customer
     */
    public function store(CustomerRequest $request)
    {
        $cust         = $request->toArray();
        $cust['slug'] = Str::slug($request->name);
        $newCust      = Customer::create($cust);

        Log::channel('cust')->info('New Customer - '.$request->name.' created by '.Auth::user()->full_name);

        return redirect(route('customers.show',$newCust->slug))->with(['message' => 'New Customer Created', 'type' => 'success']);
    }

    /**
     *  Show the customer details
     */
    public function show($id)
    {
        return Inertia::render('Customer/details');
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
    }
}
