<?php

namespace App\Http\Controllers\TechTip\Public;

use App\Actions\TechTip\TechTipSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\SearchTipsRequest;
use App\Http\Resources\PublicSearchTechTipResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchPublicTechTipController extends Controller
{
    /**
     * Search for a Public Tech Tip
     */
    public function __invoke(TechTipSearch $svc, SearchTipsRequest $request): AnonymousResourceCollection
    {
        return PublicSearchTechTipResource::collection(
            $svc($request->safe()->collect(), true)
        );
    }
}
