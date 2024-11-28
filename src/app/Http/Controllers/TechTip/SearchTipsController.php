<?php

namespace App\Http\Controllers\TechTip;

use App\Actions\TechTip\TechTipSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\SearchTipsRequest;
use Illuminate\Http\Request;

class SearchTipsController extends Controller
{
    /**
     * Handle the incoming request.
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
