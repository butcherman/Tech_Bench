<?php

namespace App\Actions\TechTip;

use App\Models\TechTip;
use Illuminate\Support\Collection;

class TechTipSearch
{
    /**
     * Perform a search for Tech Tips.  No search param returns all Models
     */
    public function __invoke(Collection $searchData, ?bool $onlyPublic = false): mixed
    {
        $searchFor = $searchData->get('searchFor');
        $equipList = $searchData->get('equipList');
        $typeList = $searchData->get('typeList');

        // If no params, return sticky and most recent Tips.
        if (empty($searchFor) && empty($equipList) && empty($typeList)) {
            return TechTip::when($onlyPublic, function ($q) {
                $q->where('public', true);
            })
                ->orderBy('sticky', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($searchData->get('perPage'));
        }

        return TechTip::search($searchFor)
            ->when(! empty($typeList), function ($q) use ($typeList) {
                // Filter by Tech Tip Type
                $q->whereIn(
                    'tip_type_id',
                    $typeList
                );
            })->when(! empty($equipList), function ($q) use ($equipList) {
                // Filter by Equipment Type
                $q->whereIn(
                    'Equipment.equip_id',
                    $equipList
                );
            })->when($onlyPublic, function ($q) {
                // Only include tips marked as public
                $q->where('public', true);
            })->paginate($searchData->get('perPage'));
    }
}
