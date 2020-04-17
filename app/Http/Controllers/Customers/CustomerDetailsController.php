<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Users\GetUserStats;
use App\Domains\Customers\DestroyCustomer;
use App\Domains\Customers\GetCustomerDetails;
use App\Domains\Customers\SetCustomerDetails;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerParentSetRequest;
use App\Http\Requests\CustomerDetailsUpdateRequest;

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
        return view('customer.newCustomer');
    }

    //  Submit the new customer form
    public function store(CustomerCreateRequest $request)
    {
        $this->authorize('hasAccess', 'Add Customer');
        $newID = (new SetCustomerDetails)->createNewCustomer($request);
        return response()->json(['success' => true, 'cust_id' => $newID]);
    }

    //  Show the customer details
    public function details($id, $name)
    {
        $details = (new GetCustomerDetails)->getDetailsWithParent($id);

        if(!$details)
        {
            return view('customer.customerNotFound');
        }

        return view('customer.details', [
            'isFav' => (new GetUserStats)->checkForCustomerFav($id) ? "true" : "false",
            'details' => $details->toJson(),
        ]);
    }

    //  Update the customer details
    public function update(CustomerDetailsUpdateRequest $request, $id)
    {
        (new SetCustomerDetails)->updateCustomerDetails($request, $id);

        return response()->json(['success' => true]);
    }

    //  Link a site to a parent site
    public function linkParent(CustomerParentSetRequest $request)
    {
        (new SetCustomerDetails)->setParentCustomerID($request);

        return response()->json(['success' => true]);
    }

    public function removeParent($id)
    {
        (new SetCustomerDetails)->removeParentCustomerID($id);

        return response()->json(['success' => true]);
    }

    //  Deactivate a customer - note this will not remove it from the database, but make it inaccessable
    public function destroy($id)
    {
        $this->authorize('hasAccess', 'Delete Customer');
        (new DestroyCustomer)->softDelete($id);

        return response()->json(['success' => true]);
    }
}
