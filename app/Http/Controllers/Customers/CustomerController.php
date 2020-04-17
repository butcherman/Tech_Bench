<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Http\Requests\CustomerSearchRequest;

use App\Domains\Users\UserFavs;
use App\Domains\Customers\CustomerSearch;
use App\Domains\Equipment\GetEquipmentData;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Landing page to search for customer
    public function index()
    {
        return view('customer.index', [
            'sysTypes' => (new GetEquipmentData)->getAllEquipmentNoCat(true),
        ]);
    }

    //  Search for a customer
    public function search(CustomerSearchRequest $request)
    {
        return (new CustomerSearch)->searchCustomer($request);
    }

    //  Check to see if a customer ID already exists
    public function checkID($id)
    {
        $cust = (new CustomerSearch)->searchCustomerID($id);

        if($cust === null)
        {
            return response()->json(['dup' => false]);
        }
        return response()->json(['dup' => true, 'name' => $cust->name]);
    }

    //  Toggle whether or not the customer is listed as a user favorite
    public function toggleFav($action, $id)
    {
        (new UserFavs)->updateCustomerFav($id);
        return response()->json(['success' => true]);
    }
}
