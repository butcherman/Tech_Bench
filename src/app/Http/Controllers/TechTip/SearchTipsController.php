<?php

namespace App\Http\Controllers\TechTip;

use App\Actions\TechTip\TechTipSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\SearchTipsRequest;

class SearchTipsController extends Controller
{
    /**
     * Perform a search for a Tech Tip.
     */
    public function __invoke(
        TechTipSearch $search,
        SearchTipsRequest $request
    ): mixed {
        return $search(
            $request->safe()->collect(),
            false
        );
    }
}
