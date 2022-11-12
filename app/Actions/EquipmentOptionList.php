<?php

namespace App\Actions;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Log;

class EquipmentOptionList
{
    public function build()
    {
        $optList = [];
        $categories = EquipmentType::all()->groupBy('EquipmentCategory.name');

        foreach($categories as $key => $equip)
        {
            foreach($equip as $e)
            {
                $optList[$key][] = $e->name;
            }
        }

        return $optList;
    }
}
