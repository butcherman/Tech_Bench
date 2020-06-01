<?php

namespace App\Domains\Equipment;

use App\SystemTypes;
use App\SystemCategories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetEquipment
{
    public function getAllEquipment($incCat = false)
    {
        if($incCat)
        {
            $list = SystemCategories::with('SystemTypes')->get();
        }
        else
        {
            $list = SystemTypes::all();
        }

        return $list;
    }

    public function getCatList()
    {
        return SystemCategories::all();
    }

    public function getEquipmentData($sysID)
    {
        return SystemTypes::where('sys_id', $sysID)->with('SystemDataFields')->first();
    }
}
