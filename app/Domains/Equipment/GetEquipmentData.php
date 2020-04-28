<?php

namespace App\Domains\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\SystemTypes;
use App\SystemCategories;
use App\Http\Resources\SystemTypesCollection;


class GetEquipmentData
{
    //  Get a list of all equipment, but exclude the category name
    public function getAllEquipmentNoCat($collection = false)
    {
        $sysList = SystemTypes::orderBy('cat_id', 'ASC')->orderBy('name', 'ASC')->get();
        Log::debug('Equipment list gathered without category list.  Data Gathered - ', array($sysList));

        if($collection)
        {
            return new SystemTypesCollection($sysList);
        }

        return $sysList;
    }

    //  Get a list of all equipment along with possible data fields
    public function getAllEquipmentWithDataList()
    {
        $equipList = SystemCategories::with('SystemTypes')->with('SystemTypes.SystemDataFields')->get();
        Log::debug('Equipment list gathered with Data Fields.  Data Gathered - ', array($equipList));

        return $equipList->forget('system_data_field_types');
    }
}
