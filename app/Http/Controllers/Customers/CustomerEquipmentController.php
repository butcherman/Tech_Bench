<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
