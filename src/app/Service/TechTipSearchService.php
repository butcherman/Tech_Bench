<?php

namespace App\Service;

use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\TechTip;
use App\Models\TechTipType;
use Illuminate\Support\Facades\Log;

class TechTipSearchService
{
    public function __construct(protected SearchTipsRequest $searchRequest)
    {
        //
    }

    /**
     * Perform a Tech Tip Search
     */
    public function search()
    {
        if (
            empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return $this->textOnlySearch();
        }

        if (
            !empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return $this->articleTypeFilter();
        }

        if (
            empty($this->searchRequest->typeList) &&
            !empty($this->searchRequest->equipList)
        ) {
            return $this->equipmentTypeFilter();
        }

        return $this->fullSearch();
    }

    /**
     * Basic search that only filters by text
     */
    protected function textOnlySearch()
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->orderBy('sticky')
            ->paginate($this->searchRequest->perPage);
    }

    /**
     * Filter by Article Type Only
     */
    protected function articleTypeFilter()
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'tip_type_id',
                $this->searchRequest->typeList
            )
            ->paginate($this->searchRequest->perPage);
    }

    /**
     * Filter by Equipment Type only
     */
    protected function equipmentTypeFilter()
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'EquipmentType.equip_id',
                $this->searchRequest->equipList
            )
            ->paginate($this->searchRequest->perPage);
    }

    /**
     * Full search with all filters enabled
     */
    protected function fullSearch()
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'tip_type_id',
                $this->searchRequest->typeList
            )
            ->whereIn(
                'EquipmentType.equip_id',
                $this->searchRequest->equipList
            )
            ->paginate($this->searchRequest->perPage);
    }
}