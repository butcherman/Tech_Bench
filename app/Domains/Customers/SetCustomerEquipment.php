<?php

namespace App\Domains\Customers;

use App\CustomerSystems;
use App\CustomerSystemData;

use Illuminate\Support\Facades\Log;

class SetCustomerEquipment extends GetCustomerDetails
{
    //  Assign a new piece of equipment to the customer
    public function createNewEquipment($request, $custID)
    {
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        $equipID = $this->createEquipment($request->sys_id, $custID, $request->shared);
        $this->processFields($request->fields, $equipID);

        return true;
    }

    //  Update the information for an existing piece of equipment
    public function updateExistingEquipment($request, $custID, $equipID)
    {
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        $this->updateEquipment($equipID, $custID, $request->shared);
        $this->processFields($request->fields, $equipID);

        return true;
    }

    //  Soft delete equipment.  Note - soft deleted equipment is cleaned up nightly during garbage collection
    public function deleteEquip($equipID)
    {
        CustomerSystems::find($equipID)->delete();
        return true;
    }

    protected function createEquipment($sysID, $custID, $shared)
    {
        $newSys = CustomerSystems::create([
            'cust_id' => $custID,
            'sys_id'  => $sysID,
            'shared'  => $shared,
        ]);

        return $newSys->cust_sys_id;
    }

    protected function updateEquipment($equipID, $custID, $shared)
    {
        CustomerSystems::find($equipID)->update([
            'cust_id' => $custID,
            'shared'  => $shared,
        ]);

        return true;
    }

    //  Go through each field attached to the equipment and update it
    protected function processFields($fieldData, $equipID)
    {
        foreach($fieldData as $field)
        {
            Log::emergency($field);
            CustomerSystemData::firstOrCreate([
                'cust_sys_id' => $equipID,
                'field_id'    => $field['field_id'],
            ],
            ['cust_sys_id' => $equipID,
                'field_id'    => $field['field_id'],
                'value' => isset($field['value']) ? $field['value'] : null, ])->update([
                'value' => isset($field['value']) ? $field['value'] : null,
            ]);
        }

        return true;
    }
}
