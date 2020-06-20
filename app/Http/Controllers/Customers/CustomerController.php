<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\Equipment\GetEquipment;
use App\Domains\Customers\CustomerSearch;
use App\Domains\Customers\GetCustomerDetails;
use App\Domains\Customers\SetCustomerDetails;
use App\Domains\User\GetUserStats;
use App\Domains\User\SetUserFavorites;
use App\Http\Requests\Customers\NewCustomerRequest;
use App\Http\Requests\Customers\CustomerSearchRequest;
use App\Http\Requests\Customers\LinkParentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index', [
            'equipList' => (new GetEquipment)->getEquipmentArray(),
        ]);
    }

    public function search(CustomerSearchRequest $request)
    {
        return (new CustomerSearch)->search($request);
    }

    public function toggleFav($custID)
    {
        $result = (new SetUserFavorites)->toggleCustomerFavorite($custID, Auth::user()->user_id);
        return response()->json(['success' => true, 'favorite' => $result]);
    }

    public function details($id)
    {
        // $custObj = new CustomerSearch;
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

    public function checkID($id)
    {
        $cust = (new CustomerSearch)->searchID($id);
        if($cust === null)
        {
            return response()->json(['duplicate' => false]);
        }

        return response()->json(['duplicate' => true, 'name' => $cust->name, 'url' => route('customer.details', [$cust->cust_id, urlencode($cust->name)])]);
    }

    public function store(NewCustomerRequest $request)
    {
        $newID = (new SetCustomerDetails)->createCustomer($request);
        Log::info('New Customer ID '.$newID.' created.  Details - ', $request->toArray());
        return response()->json(['success' => true, 'cust_id' => $newID]);
    }

    public function update(NewCustomerRequest $request, $id)
    {
        (new SetCustomerDetails)->updateCustomer($id, $request);
        Log::info('Customer ID '.$id.' updated by '.Auth::user()->full_name.'.  Details - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    public function linkParent(LinkParentRequest $request)
    {
        (new SetCustomerDetails)->linkParent($request);
        return response()->json(['success' => true]);
    }

    public function delete($custID)
    {
        (new SetCustomerDetails)->deactivateCustomer($custID);
        Log::notice('Customer ID '.$custID.' deactivated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
