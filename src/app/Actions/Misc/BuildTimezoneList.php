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
