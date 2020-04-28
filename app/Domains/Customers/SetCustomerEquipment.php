<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\CustomerSystems;
use App\CustomerSystemData;

use App\Http\Requests\CustomerNewEquipmentRequest;
use App\Http\Requests\CustomerEditEquipmentRequest;

class SetCustomerEquipment
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
    }

    //  Attach new equipment to the customer
    public function creatNewEquipment(CustomerNewEquipmentRequest $request)
    {
        $equipID = $this->validateEquipment($request->equip['sys_id']);
        if($equipID)
        {
            return false;
        }

        //  Determine if the equipment should go to the customer or their parent
        if($request->share)
        {
            $parent = Customers::find($this->custID)->parent_id;

            if($parent)
            {
                $this->custID = $parent;
            }
        }

        $newSys = CustomerSystems::create([
            'cust_id' => $this->custID,
            'sys_id' => $request->equip['sys_id'],
            'shared' => $request->share,
        ]);

        foreach($request->fields as $field)
        {
            CustomerSystemData::create([
                'cust_sys_id' => $newSys->cust_sys_id,
                'field_id'    => $field['field_id'],
                'value'       => isset($field['value']) ? $field['value'] : null,
            ]);
        }

        Log::notice('New equipment '.$request->equip['name'].' created for Customer ID '.$this->custID.' by '.Auth::user()->full_name);
        return true;
    }

    //  Update a customers exising equipment
    public function updateEquipment(CustomerEditEquipmentRequest $request)
    {
        //  Verify equipment is valid
        $equipID = $this->validateEquipment($request->equip['sys_id']); // ->cust_sys_id;
        if(!$equipID)
        {
            return false;
        }
        $this->updateEquipmentFields($equipID->cust_sys_id, $request->fields);

        Log::info('Customer Equipment Updated for Customer ID - '.$this->custID.' by '.Auth::user()->full_name.'.  Equipment Data - ', array($request->equip));
        return true;
    }

    //  Delete equipmetn that is assigned to customer
    public function deleteEquipment($equipID)
    {
        //  Get the data to make sure this is not shared
        $equip = CustomerSystems::find($equipID);
        if($equip->shared && $this->custID != $equip->cust_id)
        {
            return false;
        }

        $equip->delete();

        Log::notice('Customer Equipment ID '.$equipID.' deleted by '.Auth::user()->full_name);
        return true;
    }

    //  Verify the equipment belongs to the customer or parent customer
    protected function validateEquipment($sysID)
    {
        //  Check local customer first
        $valid = CustomerSystems::where('cust_id', $this->custID)->where('sys_id', $sysID)->first();
        Log::debug('valid data - ', array($valid));

        if(!$valid)
        {
            $parent = Customers::find($this->custID);
            if($parent)
            {
                $valid = CustomerSystems::where('cust_id', $parent->parent_id)->where('sys_id', $sysID)->first();
            }
        }

        Log::debug('new valid data - ', array($valid));
        return $valid;
    }

    //  Update the equipment data
    protected function updateEquipmentFields($equipID, $newData)
    {
        $curData = CustomerSystemData::where('cust_sys_id', $equipID)->get();
        $newData = collect($newData);

        foreach($curData as $data)
        {
            CustomerSystemData::find($data->id)->update([
                'value' => $newData->where('field_id', $data->field_id)->first()['value'],
            ]);
        }
    }
}
