<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Models\DataField;
use App\Models\CustomerEquipment;
use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentData;
use App\Http\Requests\Customers\CustomerEquipmentRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerEquipmentController extends Controller
{
    /**
     *  Store the new Customer Equipment
     */
    public function store(CustomerEquipmentRequest $request)
    {
        $cust = Customer::findOrFail($request->cust_id);
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
                'field_id'      => DataField::where('equip_id', $request->equip_id)->where('type_id', $field['type_id'])->first()->field_id,//  ???
                'value'         => isset($field['value']) ? $field['value'] : null,
            ]);
        }

        Log::channel('cust')->notice('New Equipment ID '.$request->equip_id.' has been added for Customer ID '.$request->cust_id.'('.$cust->name.') by '.$request->user()->username);
        return redirect()->back()->with(['message' => 'Successfully Added Equipment', 'type' => 'success']);
    }

    /**
     *  Ajax call to get the list of customer equipment
     */
    public function show($id)
    {
        $customer  = Customer::find($id);
        $custEquip = CustomerEquipment::where('cust_id', $id)
                ->with('CustomerEquipmentData')
                ->get();

        if($customer->parent_id)
        {
            $custEquip = CustomerEquipment::where('cust_id', $customer->parent_id)
                            ->whereShared(true)
                            ->with('CustomerEquipmentData')
                            ->get()->merge($custEquip);
        }

        return $custEquip;
    }

    /**
     *  Update the customers equipment information
     */
    public function update(CustomerEquipmentRequest $request, $id)
    {
        $cust = Customer::findOrFail($request->cust_id);

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared)
        {
            if($cust->parent_id !== null)
            {
                $request->cust_id = $cust->parent_id;
            }
        }

        CustomerEquipment::find($id)->update(['cust_id' => $request->cust_id, 'shared' => $request->shared]);

        foreach($request->data as $field)
        {
            CustomerEquipmentData::where('id', $field['id'])->where('cust_equip_id', $id)->update([
                'value' => $field['value'],
            ]);
        }

        Log::channel('cust')->notice('Customer Equipment ID '.$id.' has been updated for Customer ID '.$request->cust_id.'('.$cust->name.') by '.$request->user()->username);
        return redirect()->back()->with(['message' => 'Successfully Updated Equipment', 'type' => 'success']);
    }

    /**
     *  Remove the selected equipment
     */
    public function destroy($id)
    {
        $equip = CustomerEquipment::find($id);

        $this->authorize('delete', $equip);

        Log::channel('cust')->notice('Equipment '.$equip->name.' for Customer ID '.$equip->cust_id.' was Soft Deleted by'.Auth::user()->username);
        $equip->delete();
        return response()->noContent();
    }

    /*
    *   Restore a piece of equipment that was deleted
    */
    public function restore($id)
    {
        $equip = CustomerEquipment::withTrashed()->where('cust_equip_id', $id)->first();

        $this->authorize('restore', $equip);

        $equip->restore();
        Log::channel('cust')->info('Equipment '.$equip->equip_id.' was restored for Customer ID '.$equip->cust_id.' by '.Auth::user()->username);

        return redirect()->back();
    }

    /*
    *   Completely delete the equipment
    */
    public function forceDelete($id)
    {
        $equip = CustomerEquipment::withTrashed()->where('cust_equip_id', $id)->first();
        $this->authorize('forceDelete', $equip);

        Log::channel('cust')->alert('Equipment ID '.$id.' permanently deleted by '.Auth::user()->username);
        $equip->forceDelete();

        return redirect()->back()->with(['message' => 'Equipment permanently deleted', 'type' => 'danger']);
    }
}