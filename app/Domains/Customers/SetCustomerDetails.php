<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;

use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerParentSetRequest;
use App\Http\Requests\CustomerDetailsUpdateRequest;

class SetCustomerDetails
{
    public function createNewCustomer(CustomerCreateRequest $request)
    {
        $request->name = $this->cleanCustomerName($request->name);
        if($request->parent_id != null)
        {
            $request->parent_id = $this->verifyParentID($request->parent_id);
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

        Log::notice('New Customer ID-'.$request->custID.' created by '.Auth::user()->full_name.'.  New Customer Data - ', array($custData));
        return $custData->cust_id;
    }

    //  Update customer details
    public function updateCustomerDetails(CustomerDetailsUpdateRequest $request, $custID)
    {
        Customers::find($custID)->update([
            'name'     => $request->name,
            'dba_name' => $request->dba_name,
            'address'  => $request->address,
            'city'     => $request->city,
            'state'    => $request->state,
            'zip'      => $request->zip
        ]);

        Log::info('Customer Details Updated for Customer ID-'.$custID.' by '.Auth::user()->full_name.'.  Details - ', array($request));
        return true;
    }

    //  Add a parent ID to the customer site
    public function setParentCustomerID(CustomerParentSetRequest $request)
    {
        //  Determine if the parent assigned already has a parent
        $parentsParent = Customers::find($request->parent_id);
        if($parentsParent->parent_id)
        {
            $request->parent_id = $parentsParent->parent_id;
        }

        Customers::find($request->cust_id)->update([
            'parent_id' => $request->parent_id,
        ]);

        Log::info('Customer ID '.$request->cust_id.' was linked to parent ID '.$request->parent_id.' by '.Auth::user()->full_name);
        return true;
    }

    //  Remove the parent ID from a customer site
    public function removeParentCustomerID($custID)
    {
        Customers::find($custID)->update(['parent_id' => null]);

        Log::info('Parent Customer ID was removed for Customer ID '.$custID.' by '.Auth::user()->full_name);
        return true;
    }

    //  Remove special characters from the customers name
    protected function cleanCustomerName($name)
    {
        return str_replace('/', '-', $name);
    }

    //  Check to see if a customer ID has a Parent ID
    protected function verifyParentID($id)
    {
        $parent = Customers::find($id);

        return $parent->parent_id != null ? $parent->parent_id : $id;
    }
}
