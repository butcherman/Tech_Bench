<?php

namespace App\Http\Controllers\TechTips;

use App\Models\TechTip;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;

use Illuminate\Http\Request;

class SearchTipsController extends Controller
{
    /**
     *  Perform a search query on the existing Tech Tips
     */
    public function __invoke(SearchTipsRequest $request)
    {
        $dirty       = false;
        $searchText  = isset($request->search_text)     ? explode(' ', $request->search_text) : null;
        $searchEquip = isset($request->search_equip_id) ? $request->search_equip_id           : null;
        $searchType  = isset($request->search_type)     ? $request->search_type               : null;

        //  Determine if any search queries have been entered
        if($searchText || $searchEquip || $searchType)
        {
            $dirty = true;
        }

        //  If no search queries, send all Tech Tips (limited by pagination)
        if(!$dirty)
        {
            return TechTip::with('EquipmentType')->orderBy('sticky', 'DESC')->paginate($request->pagination_perPage);
        }

        //  Perform the search query
        return TechTip::with('EquipmentType')
            ->orderBy('sticky', 'DESC')
            //  Search text fields
            ->when($searchText, function ($q) use($searchText)
                {
                    foreach($searchText as $text)
                    {
                        $q->orWhere('subject', 'like', '%'.$text.'%')
                            ->orWhere('tip_id', 'like', '%'.$text.'%')
                            ->orWhere('details', 'like', '%'.$text.'%');
                    }
                })
            //  Search Article Type field
            ->when($searchType, function($q) use($searchType)
                {
                    $q->whereIn('tip_type_id', $searchType);
                })
            //  Search Equipment Type field
            ->when($searchEquip, function($q) use($searchEquip)
                {
                    $q->whereHas('EquipmentType', function($q2) use ($searchEquip)
                    {
                        $q2->whereIn('equipment_types.equip_id', $searchEquip);
                    });
                })
            ->paginate($request->pagination_perPage);
    }
}
