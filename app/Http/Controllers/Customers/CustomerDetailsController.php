<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        return view('customer.details');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
