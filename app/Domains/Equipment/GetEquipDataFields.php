<?php

namespace App\Domains\Equipment;

use App\SystemDataFields;
use App\SystemDataFieldTypes;

use Illuminate\Support\Facades\Log;

class GetEquipDataFields
{
    //  Pull all fields that can be assigned to the equipment - each field represents data for the customer that can be gathered for that equipment
    public function getAllFields()
    {
        return SystemDataFieldTypes::all();
    }

    //  Pull all fields with the equipment types that use the field
    public function getAllFieldsWithStats()
    {
        $data = SystemDataFieldTypes::with('SystemDataFields.SystemTypes')->get();
        return $data;
    }

    //  Get the fields for a specific type of equipment
    public function getFieldsForEquip($sysID)
    {
        return SystemDataFields::where('sys_id', $sysID)->orderBy('order')->get();
    }
}
