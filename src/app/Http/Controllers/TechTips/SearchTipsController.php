<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Service\TechTipSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchTipsController extends Controller
{
    /**
     * Perform a search for a tech tip
     */
    public function __invoke(SearchTipsRequest $request): AnonymousResourceCollection
    {
        $searchObj = new TechTipSearchService($request);

        return $searchObj->search();
    }
}
