<?php

// TODO - Refactor

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Service\TechTipSearchService;

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
