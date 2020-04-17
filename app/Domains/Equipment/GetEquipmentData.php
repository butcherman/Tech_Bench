<?php

namespace App\Domains\Equipment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\SystemTypes;
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
}
