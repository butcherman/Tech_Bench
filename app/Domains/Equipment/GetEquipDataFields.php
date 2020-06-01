<?php

namespace App\Domains\Equipment;

use App\SystemDataFields;
use App\SystemDataFieldTypes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetEquipDataFields
{
    public function getAllFields()
    {
        return SystemDataFieldTypes::all();
    }

    public function getFieldsForEquip($sysID)
    {
        return SystemDataFields::where('sys_id', $sysID)->orderBy('order')->get();
    }
}
