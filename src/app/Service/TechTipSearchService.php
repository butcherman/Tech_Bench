<?php

namespace App\Service;

use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Http\Resources\PublicTechTipResource;
use App\Http\Resources\TechTipResource;
use App\Models\TechTip;

class TechTipSearchService
{
    public function __construct(protected SearchTipsRequest $searchRequest)
    {
        //
    }

    /**
     * Standard Tech Tip Search for any logged in user
     */
    public function search()
    {
        $tipList = $this->getSearchType(false);

        return TechTipResource::collection($tipList);
    }

    /**
     * Search for only Tech Tips listed as Public
     */
    public function publicSearch()
    {
        $tipList = $this->getSearchType(true);

        return PublicTechTipResource::collection($tipList);
    }

    /**
     * Determine which search function will run
     */
    protected function getSearchType($onlyPublic = false)
    {
        if (
            empty($this->searchRequest->searchFor) &&
            empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return $this->allTechTips($onlyPublic);
        }

        if (
            empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return $this->textOnlySearch($onlyPublic);
        }

        if (
            !empty($this->searchRequest->typeList) &&
            empty($this->searchRequest->equipList)
        ) {
            return $this->articleTypeFilter($onlyPublic);
        }

        if (
            empty($this->searchRequest->typeList) &&
            !empty($this->searchRequest->equipList)
        ) {
            return $this->equipmentTypeFilter($onlyPublic);
        }

        return $this->fullSearch($onlyPublic);
    }

    /**
     * Return paginated list of all Tech Tips
     */
    protected function allTechTips($onlyPublic)
    {
        return TechTip::when($onlyPublic, function ($q) {
            $q->where('public', true);
        })->orderBy('sticky', 'desc')->orderBy('created_at', 'desc')
            ->paginate($this->searchRequest->perPage);
    }

    /**
     * Basic search that only filters by text
     */
    protected function textOnlySearch($publicOnly)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->when($publicOnly, function ($q) {
                $q->where('public', true);
            })->paginate($this->searchRequest->perPage);
    }

    /**
     * Filter by Article Type Only
     */
    protected function articleTypeFilter($publicOnly)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'tip_type_id',
                $this->searchRequest->typeList
            )->when($publicOnly, function ($q) {
                $q->where('public', true);
            })->paginate($this->searchRequest->perPage);
    }

    /**
     * Filter by Equipment Type only
     */
    protected function equipmentTypeFilter($publicOnly)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'EquipmentType.equip_id',
                $this->searchRequest->equipList
            )->when($publicOnly, function ($q) {
                $q->where('public', true);
            })->paginate($this->searchRequest->perPage);
    }

    /**
     * Full search with all filters enabled
     */
    protected function fullSearch($publicOnly)
    {
        return TechTip::search($this->searchRequest->searchFor)
            ->whereIn(
                'tip_type_id',
                $this->searchRequest->typeList
            )->whereIn(
                'EquipmentType.equip_id',
                $this->searchRequest->equipList
            )->when($publicOnly, function ($q) {
                $q->where('public', true);
            })->paginate($this->searchRequest->perPage);
    }
}
