<?php

namespace App\Service;

use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\TechTip;

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
            empty($this->searchRequest->searchFor) &&
            empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return TechTip::orderBy('sticky', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($this->searchRequest->perPage);
        }

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
     * Only allow public tech tip searches
     */
    public function publicSearch()
    {
        if (
            empty($this->searchRequest->searchFor) &&
            empty($this->searchRequest->equipList)
        ) {
            return TechTip::where('public', true)
                ->orderBy('sticky', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($this->searchRequest->perPage);
        }

        if (
            empty($this->searchRequest->equipList)
        ) {
            return $this->textOnlySearch(true);
        }

        return $this->equipmentTypeFilter(true);
    }

    /**
     * Basic search that only filters by text
     */
    protected function textOnlySearch($publicOnly = false)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->when($publicOnly, function ($q) {
                $q->where('public', true);
            })
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
    protected function equipmentTypeFilter($publicOnly = false)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'EquipmentType.equip_id',
                $this->searchRequest->equipList
            )
            ->when($publicOnly, function ($q) {
                $q->where('public', true);
            })
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
