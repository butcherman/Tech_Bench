<?php

namespace App\Actions;

use App\Jobs\UpdateCustomerDataFieldsJob;
use App\Models\DataField;
use App\Models\DataFieldType;

class OrderEquipDataTypes
{
    /**
     * Cycle through a list of data field types (for customers) to see what options have been added
     * to an equipment type and what options have been removed
     */
    public function run($dataList, $equipId)
    {
        $order   = 0;
        $fieldList = [];

        //  Input the customer information
        foreach($dataList as $field)
        {
            if($field !== null)
            {
                //  Determine if this is a new or existing field type
                $fieldID = DataFieldType::where('name', $field)->first();
                if(!$fieldID)
                {
                    $fieldID = DataFieldType::create(['name' => $field]);
                }

                //  Attach the field to the equipment type
                $dataField = DataField::updateOrCreate(
                [
                    'equip_id' => $equipId,
                    'type_id'  => $fieldID->type_id,
                ],
                [
                    'order'    => $order,
                ]);

                //  Put together a full list of the data fields to compare to customer fields later
                $fieldList[] = $dataField->field_id;

                $order++;
            }
        }

        //  Dispatch a new job to update all customers to add any missing fields
        dispatch(new UpdateCustomerDataFieldsJob($equipId, $fieldList));
    }

    public function delOptions($delArray, $equipId)
    {
        //  Remove any fields that were deleted
        foreach($delArray as $deleted)
        {
            $fieldID = DataFieldType::where('name', $deleted)->first();
            DataField::where(['equip_id' => $equipId, 'type_id' => $fieldID->type_id])->delete();
        }
    }
}
