<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Customers\GetCustomerEquipment;
use App\Domains\Customers\SetCustomerEquipment;
use App\Domains\Equipment\GetEquipment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentController extends Controller
{
    //  Get a list of all equipment that can be attached to the customer
    public function index()
    {
        return (new GetEquipment)->getAllEquipment(true);
    }

    //  Store a new equipment type for the customer
    public function store(CustomerEquipmentRequest $request)
    {
        (new SetCustomerEquipment)->createNewEquipment($request, $request->cust_id);
        return response()->json(['success' => true]);
    }

    //  Get Customer equipment
    public function show($id)
    {
        return (new GetCustomerEquipment)->execute($id);
    }

    //  Update a customers existing equipment
    public function update(CustomerEquipmentRequest $request, $id)
    {
        (new SetCustomerEquipment)->updateExistingEquipment($request, $request->cust_id, $id);
        return response()->json(['success' => true]);
    }

    //  Delete equipment from a customer
    public function destroy($id)
    {
        (new SetCustomerEquipment)->deleteEquip($id);
        Log::notice('Equipment ID '.$id.' deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
