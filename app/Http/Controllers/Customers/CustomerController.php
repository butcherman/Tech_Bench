<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Customers\CustomerSearch;
use App\Domains\Equipment\GetEquipment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSearchRequest;
use Illuminate\Http\Request;
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
}
