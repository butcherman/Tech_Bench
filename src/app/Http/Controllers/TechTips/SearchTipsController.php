<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\TechTip;
use Illuminate\Http\Request;

class SearchTipsController extends Controller
{
    /**
     * Perform a search for a tech tip
     */
    public function __invoke(SearchTipsRequest $request)
    {
        // return response()->json($request->search());
        return TechTip::search($request->searchFor)->paginate($request->perPage);
    }
}
