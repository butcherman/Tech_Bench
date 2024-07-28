<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\TechTip;
use App\Service\TechTipSearchService;
use Illuminate\Http\Request;

class SearchTipsController extends Controller
{
    /**
     * Perform a search for a tech tip
     */
    public function __invoke(SearchTipsRequest $request)
    {
        $searchObj = new TechTipSearchService($request);

        return $searchObj->search();
    }
}
