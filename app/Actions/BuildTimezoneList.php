<?php

namespace App\Actions;

use Jackiedo\Timezonelist\Facades\Timezonelist;

/**
 * Build a full list of all available time zones
 */
class BuildTimezoneList
{
    public function build()
    {
        $tzBase = Timezonelist::toArray(false);
        $tzList = [];

        foreach ($tzBase as $key => $value) {
            foreach ($value as $zone => $offset) {
                $tzList[$key][] = [
                    'text' => $offset,
                    'value' => $zone,
                ];
            }
        }

        return $tzList;
    }
}
