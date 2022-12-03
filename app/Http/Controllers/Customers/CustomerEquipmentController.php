<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use App\Models\CustomerEquipment;

class CustomerEquipmentController extends Controller
{
    /**
     * Redirecting back to customer page will refresh the equipment list
     */
    public function index()
    {
        return back();
    }

    /**
     * Store a newly created customer equipment type
     */
    public function store(CustomerEquipmentRequest $request)
    {
        $newEquipment = CustomerEquipment::create($request->only(['cust_id', 'equip_id', 'shared']));
        $request->buildEquipData($newEquipment);

        return back()->with('success', 'created');
    }

    /**
     * Update the specified Customer Equipment Data
     */
    public function update(CustomerEquipmentRequest $request, CustomerEquipment $equipment)
    {
        $request->updateEquipData($equipment);

        return back()->with('success', 'done');
    }

    /**
     * Remove the specified Customer Equipment
     */
    public function destroy(CustomerEquipment $equipment)
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();
        Log::stack(['daily', 'cust'])->info('Equipment '.$equipment->name.' deleted for Customer ID '.$equipment->cust_id.' by '.Auth::user()->username);
        return back()->with('success', 'deleted');
    }
}
