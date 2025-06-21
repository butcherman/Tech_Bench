<?php

namespace App\Actions\Misc;

use Jackiedo\Timezonelist\Facades\Timezonelist;

/*
|-------------------------------------------------------------------------------
| Create a list of available Time Zones for the application
|-------------------------------------------------------------------------------
*/

class BuildTimezoneList
{
    /**
     * @codeCoverageIgnore
     */
    public static function build(): array
    {
        $tzList = [];
        $tzBase = Timezonelist::onlyGroups(['General', 'America'])
            ->toArray(false);

        foreach ($tzBase as $key => $value) {
            $groupList = [];

            foreach ($value as $zone => $offset) {
                $groupList[] = [
                    'label' => $offset,
                    'value' => $zone,
                ];
            }

            $tzList[] = [
                'label' => $key,
                'items' => $groupList,
            ];
        }

        return $tzList;
    }
}
