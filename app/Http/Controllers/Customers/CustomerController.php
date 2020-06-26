<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\User\GetUserStats;
use App\Domains\User\SetUserFavorites;
use App\Domains\Equipment\GetEquipment;
use App\Domains\Customers\CustomerSearch;
use App\Domains\Customers\GetCustomerDetails;
use App\Domains\Customers\SetCustomerDetails;

use App\Http\Requests\Customers\LinkParentRequest;
use App\Http\Requests\Customers\NewCustomerRequest;
use App\Http\Requests\Customers\CustomerSearchRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //  Open the customer search page
    public function index()
    {
        return view('customers.index', [
            'equipList' => (new GetEquipment)->getEquipmentArray(),
        ]);
    }

    //  Do a customer search request
    public function search(CustomerSearchRequest $request)
    {
        return (new CustomerSearch)->search($request);
    }

    //  Add or remove a customer as a users favorite
    public function toggleFav($custID)
    {
        $result = (new SetUserFavorites)->toggleCustomerFavorite($custID, Auth::user()->user_id);
        return response()->json(['success' => true, 'favorite' => $result]);
    }

    //  Get the details of the specific customer
    public function details($id)
    {
        $custObj = new GetCustomerDetails;
        $details = $custObj->getDetails($id);
        if(!$details)
        {
            abort(404, 'The customer you are looking for does not exist or cannot be found');
        }

        $isFav = (new GetUserStats(Auth::user()->user_id))->checkCustomerForFav($details->cust_id);
        return view('customers.details', [
            'details' => $details,
            'parent' => $details->parent_id ? $custObj->getDetails($details->parent_id) : null,
            'isFav'  => $isFav ? true : false,
        ]);
    }

    //  Verify if a customer ID is in use or not
    public function checkID($id)
    {
        $cust = (new CustomerSearch)->searchID($id);
        if($cust === null)
        {
            return response()->json(['duplicate' => false]);
        }

        return response()->json(['duplicate' => true, 'name' => $cust->name, 'url' => route('customer.details', [$cust->cust_id, urlencode($cust->name)])]);
    }

    //  Create a new customer
    public function store(NewCustomerRequest $request)
    {
        $newID = (new SetCustomerDetails)->createCustomer($request);
        Log::info('New Customer ID '.$newID.' created.  Details - ', $request->toArray());
        return response()->json(['success' => true, 'cust_id' => $newID]);
    }

    //  Update an existing customer's basic information
    public function update(NewCustomerRequest $request, $id)
    {
        (new SetCustomerDetails)->updateCustomer($id, $request);
        Log::info('Customer ID '.$id.' updated by '.Auth::user()->full_name.'.  Details - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Set a customer as a child to a parent customer
    public function linkParent(LinkParentRequest $request)
    {
        (new SetCustomerDetails)->linkParent($request);
        return response()->json(['success' => true]);
    }

    //  Soft delete a customer
    public function delete($custID)
    {
        (new SetCustomerDetails)->deactivateCustomer($custID);
        Log::notice('Customer ID '.$custID.' deactivated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
