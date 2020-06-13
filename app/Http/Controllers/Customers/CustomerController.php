<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\Equipment\GetEquipment;
use App\Domains\Customers\CustomerSearch;
use App\Domains\Customers\SetCustomerDetails;

use App\Http\Requests\Customers\NewCustomerRequest;
use App\Http\Requests\Customers\CustomerSearchRequest;

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

    public function details($id, $name)
    {
        return response('customer '.$name);
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
}
