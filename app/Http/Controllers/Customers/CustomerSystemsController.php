<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\Equipment\GetEquipmentData;
use App\Domains\Customers\SetCustomerEquipment;
use App\Domains\Customers\GetCustomerEquipment;

use App\Http\Requests\CustomerNewEquipmentRequest;
use App\Http\Requests\CustomerEditEquipmentRequest;

class CustomerSystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Get the possible system types that can be assigned to the customer
    public function index()
    {
        return  (new GetEquipmentData)->getAllEquipmentWithDataList();
    }

    //  Store a new system for the customer
    public function store(CustomerNewEquipmentRequest $request)
    {
        $equipObj = new SetCustomerEquipment($request->cust_id);
        if(!$equipObj->creatNewEquipment($request))
        {
            abort(404);
        }

        return response()->json(['success' => true]);
    }

    //  Get the list of systems attached to the customer
    public function show($id)
    {
        return (new GetCustomerEquipment($id))->execute();
    }

    // Update the customers system data
    public function update(CustomerEditEquipmentRequest $request, $id)
    {
        $updateObj = new SetCustomerEquipment($id);
        if(!$updateObj->updateEquipment($request))
        {
            abort(404);
        }

        return response()->json(['success' => true]);
    }

    //  Delete a system attached to a customer
    public function deleteEquip($equipID, $custID)
    {
        $delObj = new SetCustomerEquipment($custID);
        if(!$delObj->deleteEquipment($equipID))
        {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
}
