<?php

namespace App\Domains\TechTips;

use App\TechTips;

use Illuminate\Support\Facades\Log;

class SearchTips
{
    protected $perPage = 10;

    public function execute($request)
    {
        $searchData    = $request->search;
        $this->perPage = $request->pagination['perPage'];

        if($searchData)
        {
            $results = $this->searchFor($searchData);
        }
        else
        {
            $results = $this->getAllTips();
        }

        Log::debug('Tech Tip Search performed for ', $request->toArray());
        Log::debug('Tech Tip Search results - ', $results != null ? $results->toArray() : []);
        return $results;
    }

    //  Return all tips - no search paramaters
    protected function getAllTips()
    {
        return TechTips::orderBy('sticky', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->with('SystemTypes')
            ->with('TechTipTypes')
            ->paginate($this->perPage);
    }

    //  Process all of the search filters to search for a tech tip
    protected function searchFor($search)
    {
        $searchText = isset($search['text'])   ? explode(' ', $search['text']) : null;
        $type       = isset($search['type'])   ? $search['type'] : null;
        $sys        = isset($search['sys_id']) ? $search['sys_id'] : null;

        return TechTips::orderBy('sticky', 'DESC')
            ->orderBy('created_at', 'DESC')
            //  Search text fields
            ->when(!empty($searchText), function($q) use ($searchText)
            {
                foreach($searchText as $text)
                {
                    $q->orWhere('subject', 'like', '%'.$text.'%')
                        ->orWhere('tip_id', 'like', '%'.$text.'%')
                        ->orWhere('description', 'like', '%'.$text.'%');
                }
            })
            //  Search Article Type fields
            ->when($type, function($q) use ($type)
            {
                $q->whereIn('tip_type_id', $type);
            })
            //  Search equipment type fields
            ->when($sys, function($q) use ($sys)
            {
                $q->whereHas('SystemTypes', function($q2) use ($sys)
                {
                    $q2->whereIn('system_types.sys_id', $sys);
                });
            })
            ->with('SystemTypes')
            ->with('TechTipTypes')
            ->paginate($this->perPage);
    }
}
