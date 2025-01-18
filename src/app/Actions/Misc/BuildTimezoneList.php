<?php

namespace App\Actions\Misc;

use Jackiedo\Timezonelist\Facades\Timezonelist;

class BuildTimezoneList
{
    public function build(): array
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
