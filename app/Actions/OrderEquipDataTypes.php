<?php

namespace App\Actions;

use App\Models\DataField;
use App\Models\DataFieldType;

class OrderEquipDataTypes
{
    public function run($dataList, $equipId)
    {
        $order = 0;

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
                DataField::updateOrCreate(
                [
                    'equip_id' => $equipId,
                    'type_id'  => $fieldID->type_id,
                ],
                [
                    'order'    => $order,
                ]);

                $order++;
            }
        }
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
