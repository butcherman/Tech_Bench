<?php

namespace App\Http\Controllers\Customers;

use App\Events\NewCustomerCreated;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\NewCustomerRequest;
use App\Models\Customer;
use App\Models\EquipmentType;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Search page for finding a customer
     */
    public function index(Request $request)
    {
        return Inertia::render('Customers/Index', [
            'create'      => $request->user()->can('create', Customer::class),
            'equip_types' => EquipmentType::orderBy('cat_id')->get()->pluck('name')->values(),
        ]);
    }

    /**
     * Show the form for creating a new Customer
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
        return Inertia::render('Customers/Create');
    }

    /**
     * Create a new Customer
     */
    public function store(NewCustomerRequest $request)
    {
        $cust         = $request->toArray();
        $cust['slug'] = Str::slug($request->name);
        $newCust      = Customer::create($cust);

        // Log::channel('cust')->info('New Customer - '.$request->name.' created by '.Auth::user()->full_name);

        event(new NewCustomerCreated($newCust));
        return redirect(route('customers.show',$newCust->slug))->with(['message' => 'New Customer Created', 'type' => 'success']);
    }

    /**
     * Display the Customers Information
     */
    public function show($id)
    {
        //
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
