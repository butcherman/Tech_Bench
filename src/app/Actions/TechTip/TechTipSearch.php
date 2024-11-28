<?php

namespace App\Actions\TechTip;

use App\Models\TechTip;
use Illuminate\Support\Collection;

class TechTipSearch
{
    /**
     * Only return tips marked as public
     *
     * @var bool
     */
    protected $isPublic;

    /**
     * Text value to search for
     *
     * @var string
     */
    protected $searchText;

    /**
     * Tech Tip Type to include in search
     *
     * @var array<int, int>
     */
    protected $searchType;

    /**
     * Equipment List to include in search
     *
     * @var array<int, int>
     */
    protected $searchEquip;

    /*
    |---------------------------------------------------------------------------
    | Perform a search for a Tech Tip.  No search param returns all Models.
    |---------------------------------------------------------------------------
    */
    public function __invoke(Collection $searchData, ?bool $isPublic = true): mixed
    {
        $this->isPublic = $isPublic;
        $this->searchText = $searchData->get('searchFor');
        $this->searchType = $searchData->get('typeList');
        $this->searchEquip = $searchData->get('equipList');

        // If any search parameters are filled out, do a Scout Search.
        if ($this->hasSearchParam()) {
            return $this->performSearch($searchData->get('perPage'));
        }

        // If no search parameters are filled out, return all models.
        return TechTip::when($this->isPublic, function ($q) {
            $q->where('public', true);
        })->orderBy('sticky', 'desc')->orderBy('created_at', 'desc')
            ->paginate($searchData->get('perPage'));
    }

    /**
     * Determine if any of the search fields are filled out
     */
    protected function hasSearchParam(): bool
    {
        if ($this->searchText) {
            return true;
        }

        if (count($this->searchType)) {
            return true;
        }

        if (count($this->searchEquip)) {
            return true;
        }

        return false;
    }

    /**
     * Perform the custom search with text and filters
     */
    protected function performSearch(int $perPage)
    {
        return TechTip::search($this->searchText)
            ->when(count($this->searchType), function ($q) {
                $q->whereIn('tip_type_id', $this->searchType);
            })
            ->when(count($this->searchEquip), function ($q) {
                $q->whereIn('EquipmentType.equip_id', $this->searchEquip);
            })
            ->when($this->isPublic, function ($q) {
                $q->where('public', true);
            })->paginate($perPage);
    }
}
