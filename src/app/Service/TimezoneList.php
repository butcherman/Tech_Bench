<?php

namespace App\Service;

use Jackiedo\Timezonelist\Facades\Timezonelist as FacadesTimezonelist;

class TimezoneList
{
    /**
     * Build a custom version of the Timezone List that will match to the
     * custom Vue Select Box
     */
    public static function Build()
    {
        $tzBase = FacadesTimezonelist::toArray(false);
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
