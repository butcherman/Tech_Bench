<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;

use App\Models\Customer;
use App\Models\EquipmentType;
use App\Http\Controllers\Controller;
use App\Models\UserCustomerBookmark;
use App\Http\Requests\Customers\CustomerRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     *  Customer search page
     */
    public function index()
    {
        return Inertia::render('Customer/index', [
            'can_create'  => Auth::user()->can('create', Customer::class),
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
        //  Check if we are passing the customer slugged name, or customer ID number
        if(is_numeric($id))
        {
            //  To keep things uniform, redirect to a link that has the customers name rather than the ID
            $customer = Customer::findOrFail($id);
            return redirect(route('customers.show', $customer->slug));
        }

        $customer = Customer::where('slug', $id)->orWhere('cust_id', $id)->with('Parent')->with('CustomerEquipment.CustomerEquipmentData')->firstOrFail();
        $isFav    = UserCustomerBookmark::where('user_id', Auth::user()->user_id)->where('cust_id', $customer->cust_id)->count();

        return Inertia::render('Customer/details', [
            'details' => $customer,
            'user_functions' => [
                'fav'  => $isFav,                                           //  Customer is bookmarked by the user
                'edit' => Auth::user()->can('update', $customer),           //  User is allowed to edit the customers basic details
            ],
        ]);
    }

    /**
     *  TODO - Go away???
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     *  Update the customers basic information
     */
    public function update(CustomerRequest $request, $id)
    {
        $cust         = $request->toArray();
        $cust['slug'] = Str::slug($request->name);
        Customer::find($id)->update($cust);

        return redirect(route('customers.show', $cust['slug']))->with(['message' => 'Customer Details Updated', 'type' => 'success']);
    }

    /**
     *  Deactivate the customer
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
