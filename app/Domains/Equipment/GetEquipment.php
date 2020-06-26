<?php

namespace App\Domains\Equipment;

use App\SystemTypes;
use App\SystemCategories;

use Illuminate\Support\Facades\Log;

class GetEquipment
{
    //  Pull all equpment types - sorting by category is optional
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

    //  Return an array that can be easily assigned by vue v-for
    public function getEquipmentArray()
    {
        $list = SystemTypes::orderBy('cat_id', 'ASC')->orderBy('name', 'ASC')->get();

        $newList = [];
        foreach($list as $item)
        {
            $newList[] = [
                'value' => $item->sys_id,
                'text'  => $item->name,
            ];
        }

        return $newList;
    }

    //  Get the list of categories
    public function getCatList()
    {
        return SystemCategories::all();
    }

    //  Get all fields attached to the equipment
    public function getEquipmentData($sysID)
    {
        return SystemTypes::where('sys_id', $sysID)->with('SystemDataFields')->first();
    }
}
