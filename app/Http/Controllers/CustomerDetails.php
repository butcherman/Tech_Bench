<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\CustomerFavs;
use App\Customers;

class CustomerDetails extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Open the new customer form
    public function create()
    {
        return view('customer.form.newCustomer');
    }

    //  Store the new customer form
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cust_id'  => 'required|numeric|unique:customers',
            'name'     => 'required|unique:customers',
            'dba_name' => 'nullable',
            'address'  => 'required',
            'city'     => 'required',
            'zip'      => 'required|numeric'
        ]);
        
        Customers::create($request->all());
        
        //  Create the customer data folder
        $path = env('CUST_FOLDER', false).DIRECTORY_SEPARATOR.$request['cust_id'];
        Storage::makeDirectory($path);
        
        return view('customer.newCustomer', [
            'cust_id' => $request->cust_id,
            'cust_name' => urlencode($request->name)
        ]);
    }

    //  Display customer details
    public function details($id, $name)
    {
        $custDetails = Customers::find($id);
        
        //  Check for empty data set
        if(empty($custDetails))
        {
            return view('err.customerNotFound');
        }

        $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custDetails->cust_id)->first();
        
        return view('customer.details', [
            'details' => $custDetails,
            'isFav' => $custFav
        ]);
    }

    //  Show the customer edit form
    public function edit($id)
    {
        return view('customer/form.editCustomer', [
            'cust' => Customers::find($id)
        ]);
    }

    //  Update the customer details
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'     => 'required',
            'dba_name' => 'nullable',
            'address'  => 'required',
            'city'     => 'required',
            'zip'      => 'required|numeric'
        ]);
        
        $custData = Customers::find($id)->update($request->all());
        
        return redirect()->route('customer.id.show', [
            'id' => urlencode($request->name)
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
