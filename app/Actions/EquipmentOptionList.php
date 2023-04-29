<?php

namespace App\Actions;

use App\Models\EquipmentType;

class EquipmentOptionList
{
    public function build()
    {
        $optList = [];
        $categories = EquipmentType::all()->groupBy('EquipmentCategory.name');

        foreach ($categories as $key => $equip) {
            foreach ($equip as $e) {
                $optList[$key][] = $e->name;
            }
        }

        return $optList;
    }
}
