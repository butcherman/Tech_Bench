<?php

namespace App\Domains\TechTips;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

use App\TechTips;
use App\TechTipFiles;

use App\Http\Resources\TechTipsCollection;
use App\Http\Requests\TechTipSearchRequest;

class GetTechTips
{
    public function searchTips(TechTipSearchRequest $request)
    {
        $searchFields = $request->search;

        //  See if there are any search paramaters entered
        if($searchFields['searchText'] == null && !Arr::has($searchFields, 'articleType') && !Arr::has($searchFields, 'systemType'))
        {
            //  No search paramaters, send all tech tips
            $tips = new TechTipsCollection(
                TechTips::orderBy('sticky', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->with('SystemTypes')
                    ->paginate($request->pagination['perPage']
            ));
        }
        else
        {
            $article = Arr::has($searchFields, 'articleType');
            $system  = Arr::has($searchFields, 'systemType');
            //  Search paramaters, filter results
            $tips = new TechTipsCollection(
                TechTips::orderBy('sticky', 'DESC')->orderBy('created_at', 'DESC')
                    //  Search by id or a phrase in the title or description
                    ->where(function($query) use ($searchFields) {
                        $query->where('subject', 'like', '%'.$searchFields['searchText'].'%')
                            ->orWhere('tip_id', 'like', '%'.$searchFields['searchText'].'%')
                            ->orWhere('description', 'like', '%'.$searchFields['searchText'].'%');
                    })
                    ->when($article, function($query) use ($searchFields) {
                        $query->whereIn('tip_type_id', $searchFields['articleType']);
                    })
                    ->when($system, function($query) use ($searchFields) {
                        $query->whereHas('SystemTypes', function($query) use ($searchFields) {
                            $query->whereIn('system_types.sys_id', $searchFields['systemType']);
                        });
                    })
                    ->with('SystemTypes')
                    ->paginate($request->pagination['perPage'])
            );
        }

        Log::debug('Tech Tip search query performed.  Results - ', array($tips));
        return $tips;
    }

    //  Get the details about a tech tip
    public function getTipDetails($tipID)
    {
        $tipData = TechTips::where('tip_id', $tipID)->with('User')->with('SystemTypes')->first();
        $files = TechTipFiles::where('tip_id', $tipID)->with('Files')->get();

        return collect(['details' => $tipData, 'files' => $files]);
    }
}
