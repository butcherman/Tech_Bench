<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerFavs;
use App\PhoneNumberType;
use Illuminate\Http\Request;
use App\Http\Traits\SystemsTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

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
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('customer.newCustomer');
    }

    //  Submit the new customer form
    public function store(Request $request)
    {
        $request->validate([
            'custID'   => 'required|numeric|unique:customers,cust_id',
            'custName' => 'required', // |unique:customers,name',
            'custDBA'  => 'nullable',
            'custAddr' => 'required',
            'custCity' => 'required',
            'custZip'  => 'required|numeric'
        ]);
        
        //  Remove any forward slash (/) from the Customer name field
        $request->merge(['custName' => str_replace('/', '-', $request->custName)]);
        
        Customers::create([
            'cust_id'  => $request->custID,
            'name'     => $request->custName,
            'dba_name' => $request->custDBA,
            'address'  => $request->custAddr,
            'city'     => $request->custCity,
            'state'    => $request->selectedState,
            'zip'      => $request->custZip,
            'active'   => 1
        ]);
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('New Customer ID-'.$request->custID.' created by User ID-'.Auth::user()->user_id);
        
        return response()->json([
            'success' => true, 
            'url' => route('customer.details', [$request->custID, urlencode($request->custName)])]);
    }
    
    //  Show the customer details
    public function details($id, $name)
    {
        $custDetails = Customers::find($id);
        $allSystems  = $this->getAllSystems();
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        if(empty($custDetails))
        {
            Log::info('User ID-'.Auth::user()->user_id.' visited invalid customer ID-'.$id.'-'.$name);
            return view('err.customerNotFound');
        }
        
        //  Determine if the customer is one of the users bookmarks
        $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custDetails->cust_id)->first();
        //  Get the types of phone numbers that can be assigned to a customer contact
        $pTypes = PhoneNumberType::all();
        $phoneTypes = [];
        foreach($pTypes as $type)
        {
            $phoneTypes[] = [
                'value' => $type->phone_type_id,
                'text'  => $type->description,
                'icon'  => $type->icon_class
            ];
        }
        
//        Log::debug('Customer Details', $custDetails->toArray());
        return view('customer.details', [
            'details'    => $custDetails,
            'isFav'      => empty($custFav) ? false : true,
            'sysList'    => $allSystems,
            'phoneTypes' => $phoneTypes
        ]);
    }

    //  Get the basic details of the customer
    public function show($id)
    {
        $details = Customers::find($id);
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        if(empty($details))
        {
            Log::info('User ID-'.Auth::user()->user_id.' visited invalid customer ID-'.$id);
            return response()->json(['error' => 'Customer Not Found']);
        }
        
//        Log::debug('Customer Details', $details->toArray());
        return response()->json($details);
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
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer Details Updated for Customer ID-'.$id.' by User ID-'.Auth::user()->user_id);
//        Log::debug('Customer Details Submitted - ', $request->toArray());
        return response()->json([
            'success' => true
        ]);
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
