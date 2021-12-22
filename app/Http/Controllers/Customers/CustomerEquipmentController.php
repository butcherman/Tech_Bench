<?php

namespace App\Http\Controllers\Customers;

use App\Events\Customers\Equipment\CustomerEquipmentAddedEvent;
use App\Events\Customers\Equipment\CustomerEquipmentDeletedEvent;
use App\Events\Customers\Equipment\CustomerEquipmentRestoredEvent;
use App\Events\Customers\Equipment\CustomerEquipmentForceDeletedEvent;

use App\Events\Customers\Equipment\CustomerEquipmentUpdatedEvent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;

use App\Models\Customer;
use App\Models\DataField;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;

class CustomerEquipmentController extends Controller
{
    /**
     * Store new equipment for a customer
     */
    public function store(CustomerEquipmentRequest $request)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        //  Input the equipment type
        $newEquip = CustomerEquipment::create([
            'cust_id'  => $cust_id,
            'equip_id' => $request->equip_id,
            'shared'   => $request->shared,
        ]);

        //  Input the equipment data
        foreach($request->data as $field)
        {
            CustomerEquipmentData::create([
                'cust_equip_id' => $newEquip->cust_equip_id,
                'field_id'      => DataField::where('equip_id', $request->equip_id)->where('type_id', $field['type_id'])->first()->field_id, //  ???
                'value'         => isset($field['value']) ? $field['value'] : null,
            ]);
        }

        event(new CustomerEquipmentAddedEvent($cust, $newEquip));
        return redirect()->back()->with(['message' => 'Successfully Added Equipment', 'type' => 'success']);
    }

    /**
     * Update the equipment for the customer
     */
    public function update(CustomerEquipmentRequest $request, $id)
    {
        //  If the equipment is shared, it must be assigned to the parent site
        $cust = Customer::findOrFail($request->cust_id);
        if($request->shared)
        {
            if($cust->parent_id !== null)
            {
                $request->cust_id = $cust->parent_id;
            }
        }

        //  Update the Customer ID and Shared status of the equipment
        $equip = CustomerEquipment::find($id);
        $equip->update(['cust_id' => $request->cust_id, 'shared' => $request->shared]);

        //  Insert the data for the equipment into the database
        foreach($request->data as $field)
        {
            CustomerEquipmentData::where('id', $field['id'])->where('cust_equip_id', $id)->update([
                'value' => $field['value'],
            ]);
        }

        event(new CustomerEquipmentUpdatedEvent($cust, $equip));
        return redirect()->back()->with(['message' => 'Successfully Updated Equipment', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        $equip = CustomerEquipment::find($id);
        $cust  = Customer::find($equip->cust_id);
        $this->authorize('delete', $equip);
        $equip->delete();

        event(new CustomerEquipmentDeletedEvent($cust, $equip));
        return back()->with([
            'message' => 'Equipment Deleted',
            'type'    => 'warning',
        ]);
    }

    /*
    *   Restore a piece of equipment that was deleted
    */
    public function restore($id)
    {
        $equip = CustomerEquipment::withTrashed()->where('cust_equip_id', $id)->first();

        $this->authorize('restore', $equip);
        $equip->restore();

        event(new CustomerEquipmentForceDeletedEvent(Customer::find($equip->cust_id), $equip));
        return back()->with([
            'message' => 'Equipment Restored',
            'type'    => 'success',
        ]);
    }

    /*
    *   Completely delete the equipment
    */
    public function forceDelete($id)
    {
        $equip = CustomerEquipment::withTrashed()->where('cust_equip_id', $id)->first();
        $this->authorize('forceDelete', $equip);
        $equip->forceDelete();

        event(new CustomerEquipmentRestoredEvent(Customer::find($equip->cust_id), $equip));
        return back()->with(['message' => 'Equipment permanently deleted', 'type' => 'danger']);
    }
}
