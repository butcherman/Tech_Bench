<?php

namespace App\Actions;

use App\Models\EquipmentType;

/**
 * Group equipment by categories and place in an associative array that
 * will be used in a Select box.
 */
class EquipmentOptionsList
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
