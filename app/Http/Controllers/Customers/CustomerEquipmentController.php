<?php

namespace App\Http\Controllers\Customers;

use App\Events\Customer\CustomerEquipmentCreatedEvent;
use App\Events\Customer\CustomerEquipmentUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use App\Models\CustomerEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentController extends Controller
{
    /**
     * Store a newly created customer equipment type
     */
    public function store(CustomerEquipmentRequest $request)
    {
        $newEquipment = CustomerEquipment::create($request->only(['cust_id', 'equip_id', 'shared']));
        $request->buildEquipData($newEquipment);

        Log::stack(['daily', 'cust'])->info('New Customer Equipment '.$newEquipment->name.' created by '.$request->user()->username.' for '.$newEquipment->Customer->name);
        event(new CustomerEquipmentCreatedEvent($newEquipment->Customer, $newEquipment));

        return back()->with('success', __('cust.equipment.created', ['equip' => $newEquipment->name]));
    }

    /**
     * Update the specified Customer Equipment Data
     */
    public function update(CustomerEquipmentRequest $request, CustomerEquipment $equipment)
    {
        $request->updateEquipData($equipment);
        event(new CustomerEquipmentUpdatedEvent($equipment->Customer, $equipment));

        return back()->with('success', __('cust.equipment.updated', ['equip' => $equipment->name]));
    }

    /**
     * Remove the specified Customer Equipment
     */
    public function destroy(CustomerEquipment $equipment)
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();
        Log::stack(['daily', 'cust'])->info('Equipment '.$equipment->name.' deleted for Customer ID '.$equipment->cust_id.' by '.Auth::user()->username);

        return back()->with('success', __('cust.equipment.deleted', ['equip' => $equipment->name]));
    }

    /**
     * Restore a soft deleted item
     */
    public function restore(CustomerEquipment $equipment)
    {
        $this->authorize('restore', $equipment);

        $equipment->restore();
        Log::stack(['daily', 'cust'])->info('Equipment '.$equipment->name.' has been restored for customer '.$equipment->cust_id.' by '.Auth::user()->username);

        return back()->with('success', __('cust.equipment.restored', ['equip' => $equipment->name]));
    }

    /**
     * Force delete equipment forever
     */
    public function forceDelete(CustomerEquipment $equipment)
    {
        $this->authorize('forceDelete', $equipment);

        $equipment->forceDelete();
        Log::stack(['daily', 'cust'])->notice('Equipment '.$equipment->name.' has been force deleted for customer ID '.$equipment->cust_id.' by '.Auth::user()->username);

        return back()->with('danger', __('cust.equipment.force_deleted', ['equip' => $equipment->name]));
    }
}
