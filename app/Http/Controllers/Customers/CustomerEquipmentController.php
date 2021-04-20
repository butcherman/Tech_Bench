<?php

namespace App\Http\Controllers\Customers;

use App\Models\DataField;
use App\Models\CustomerEquipment;
use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentData;
use App\Http\Requests\Customers\CustomerEquipmentRequest;

use Illuminate\Support\Facades\Log;

class CustomerEquipmentController extends Controller
{
    /**
     *  Store the new Customer Equipment
     */
    public function store(CustomerEquipmentRequest $request)
    {
        //  Input the equipment type
        $newEquip = CustomerEquipment::create($request->only(['cust_id', 'equip_id', 'shared']));

        //  Input the equipment data
        foreach($request->data as $field)
        {
            CustomerEquipmentData::create([
                'cust_equip_id' => $newEquip->cust_equip_id,
                'field_id'      => DataField::where('equip_id', $request->equip_id)->where('type_id', $field['type_id'])->first()->field_id,//  ???
                'value'         => isset($field['value']) ? $field['value'] : null,
            ]);
        }

        Log::notice('New Equipment ID '.$request->equip_id.' has been added for Customer ID '.$request->cust_id);
        return back()->with(['message' => 'Successfully Added Equipment', 'type' => 'success']);
    }

    /**
     *  Ajax call to get the list of customer equipment
     */
    public function show($id)
    {
        return CustomerEquipment::with('CustomerEquipmentData')->where('cust_id', $id)->get();
    }

    /**
     *  Update the customers equipment information
     */
    public function update(CustomerEquipmentRequest $request, $id)
    {
        foreach($request->data as $field)
        {
            CustomerEquipmentData::where('id', $field['id'])->where('cust_equip_id', $id)->update([
                'value' => $field['value'],
            ]);
        }

        Log::notice('Customer Equipment ID '.$id.' has been updated for Customer ID '.$request->cust_id);
        return back()->with(['message' => 'Successfully Updated Equipment', 'type' => 'success']);
    }

    /**
     *  Remove the selected equipment
     */
    public function destroy($id)
    {
        CustomerEquipment::find($id)->delete();
        Log::notice('Equipment ID '.$id.' for a customer was deleted');

        return response()->noContent();
    }
}
