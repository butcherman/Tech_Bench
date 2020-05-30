<?php

namespace App\Domains\Equipment;

use App\SystemCategories;
use App\SystemTypes;
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
}
