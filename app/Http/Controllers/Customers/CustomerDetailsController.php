<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerFavs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CustomerDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //  New Customer Form
    public function create()
    {
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
        
        Log::info('New Customer ID-'.$request->custID.' created by User ID-'.Auth::user()->user_id);
        
        return response()->json([
            'success' => true, 
            'url' => route('customer.details', [$request->custID, urlencode($request->custName)])]);
    }
    
    //  Show the customer details
    public function details($id, $name)
    {
        $custDetails = Customers::find($id);
        
        if(empty($custDetails))
        {
            return view('err.customerNotFound');
        }
        
        $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custDetails->cust_id)->first();
                
        return view('customer.details', [
            'details' => $custDetails,
            'isFav'   => empty($custFav) ? false : true
        ]);
    }

    //  Get the basic details of the customer
    public function show($id)
    {
        $details = Customers::find($id);
        
        if(empty($details))
        {
            return response()->json(['error' => 'Customer Not Found']);
        }
        
        return response()->json($details);
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
