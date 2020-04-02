<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerFavs;
use App\PhoneNumberTypes;
use App\CustomerFileTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\PhoneNumberTypesCollection;
use App\Http\Resources\CustomerFileTypesCollection;

class CustomerDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  New Customer Form
    public function create()
    {
        $this->authorize('hasAccess', 'Add Customer');

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('customer.newCustomer');
    }

    //  Submit the new customer form
    public function store(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());
        $this->authorize('hasAccess', 'Add Customer');

        $request->validate([
            'cust_id'   => 'nullable|numeric|unique:customers,cust_id',
            'parent_id' => 'nullable|numeric|exists:customers,cust_id',
            'name'      => 'required',
            'dba_name'  => 'nullable',
            'address'   => 'required',
            'city'      => 'required',
            'zip'       => 'required|numeric'
        ]);

        //  Remove any forward slash (/) from the Customer name field
        $request->merge(['name' => str_replace('/', '-', $request->name)]);

        //  Check if the parent ID noted, has a parent of its own
        if($request->parent_id)
        {
            $parentsParent = Customers::find($request->parent_id);

            if($parentsParent->parent_id)
            {
                $request->parent_id = $parentsParent->parent_id;
            }
        }

        $custData = Customers::create([
            'cust_id'   => $request->cust_id,
            'parent_id' => $request->parent_id,
            'name'      => $request->name,
            'dba_name'  => $request->dba_name,
            'address'   => $request->address,
            'city'      => $request->city,
            'state'     => $request->selectedState,
            'zip'       => $request->zip,
        ]);

        Log::notice('New Customer ID-'.$request->custID.' created by '.Auth::user()->full_name);
        return response()->json(['success' => true, 'cust_id' => $custData->cust_id]);
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

        $custFav   = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custDetails->cust_id)->first();
        $numTypes  = new PhoneNumberTypesCollection(PhoneNumberTypes::all());
        $fileTypes = new CustomerFileTypesCollection(CustomerFileTypes::all());
        $parent    = $custDetails->parent_id ? Customers::find($custDetails->parent_id)->name : null;

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('customer.details', [
            'cust_id'     => $custDetails->cust_id,
            'details'     => $custDetails->toJson(),
            'isFav'       => empty($custFav) ? 'false' : 'true',
            'numberTypes' => $numTypes,
            'fileTypes'   => $fileTypes,
            'parent'      => $parent,
            'linked'      => $custDetails->child_count || $parent ? 'true' : 'false',
        ]);
    }

    //  Update the customer details
    public function update(Request $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

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

        Log::info('Customer Details Updated for Customer ID-'.$id.' by User ID-'.Auth::user()->user_id);
        return response()->json([
            'success' => true
        ]);
    }

    //  Link a site to a parent site
    public function linkParent(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'parent_id' => 'required|numeric|exists:customers,cust_id',
            'cust_id'   => 'required|numeric|exists:customers,cust_id'
        ]);

        $parentsParent = Customers::find($request->parent_id);

        if($parentsParent->parent_id)
        {
            $request->parent_id = $parentsParent->parent_id;
        }

        Customers::find($request->cust_id)->update([
            'parent_id' => $request->parent_id,
        ]);

        Log::info('Customer ID '.$request->cust_id.' was linked to parent ID '.$request->parent_id.' by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }


    public function removeParent($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        Customers::find($id)->update(['parent_id' => null]);
        Log::info('Parent Customer ID was removed for Customer ID '.$id.' by '.Auth::user()->full_name);
    }

    //  Deactivate a customer - note this will not remove it from the database, but make it inaccessable
    public function destroy($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        $this->authorize('hasAccess', 'Deactivate Customer');

        //  Remove the customer from any users favorites
        CustomerFavs::where('cust_id', $id)->delete();

        //  Disable the tip
        Customers::destroy($id);

        Log::notice('User - '.Auth::user()->full_name.' has deactivated Customer ID '.$id);
    }
}
