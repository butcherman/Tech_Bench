<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerFavs;
use App\PhoneNumberType;
use App\CustomerFileTypes;
use Illuminate\Http\Request;
use App\Http\Traits\SystemsTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\Customers as CustomersResource;
use App\Http\Resources\CustomersCollection;

class CustomerDetailsController extends Controller
{
    use SystemsTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //  New Customer Form
    public function create()
    {
        $this->authorize('hasAccess', 'add_customer');

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('customer.newCustomer');
    }

    //  Submit the new customer form
    public function store(Request $request)
    {
        $this->authorize('hasAccess', 'add_customer');

        $request->validate([
            'cust_id'  => 'required|numeric|unique:customers,cust_id',
            'name'     => 'required', // |unique:customers,name',
            'dba_name' => 'nullable',
            'address'  => 'required',
            'city'     => 'required',
            'zip'      => 'required|numeric'
        ]);

        //  Remove any forward slash (/) from the Customer name field
        $request->merge(['name' => str_replace('/', '-', $request->name)]);

        Customers::create([
            'cust_id'  => $request->cust_id,
            'name'     => $request->name,
            'dba_name' => $request->dba_name,
            'address'  => $request->address,
            'city'     => $request->city,
            'state'    => $request->selectedState,
            'zip'      => $request->zip,
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('New Customer ID-'.$request->custID.' created by User ID-'.Auth::user()->user_id);

        return response()->json(['success' => true ]);
    }

    //  Show the customer details
    public function details($id, $name)
    {
        $custDetails = Customers::find($id);

        if($custDetails === null)
        {
            Log::info('User - '.Auth::user()->user_id.' visited invalid customer ID - '.$id.' - '.$name);
            return view('customer.customerNotFound');
        }

        $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custDetails->cust_id)->first();

        // Log::debug('Customer Details', $custDetails->toArray());
        return view('customer.details', [
            'cust_id' => $custDetails->cust_id,
            'details' => $custDetails->toJson(),
            'isFav'   => empty($custFav) ? 'false' : 'true',
            'canDel'  => Gate::allows('hasAccess', 'deactivate_customer'),
        ]);
    }

    //  Update the customer details
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required',
            'dba_name' => 'nullable',
            'address'  => 'required',
            'city'     => 'required',
            'state'    => 'required',
            'zip'      => 'required|numeric'
        ]);

        Customers::find($id)->update([
            'name'     => $request->name,
            'dba_name' => $request->dba_name,
            'address'  => $request->address,
            'city'     => $request->city,
            'state'    => $request->state,
            'zip'      => $request->zip
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer Details Updated for Customer ID-'.$id.' by User ID-'.Auth::user()->user_id);
        Log::debug('Customer Details Submitted - ', $request->toArray());
        return response()->json([
            'success' => true
        ]);
    }

    //  Deactivate a customer - note this will not remove it from the database, but make it inaccessable
    public function destroy($id)
    {
        $this->authorize('hasAccess', 'deactivate_customer');
        Customers::destroy($id);

        Log::notice('User - '.Auth::user()->user_id.' has deactivated Customer ID '.$id);
    }
}
