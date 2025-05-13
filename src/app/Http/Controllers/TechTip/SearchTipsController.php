<?php

namespace App\Http\Controllers\TechTip;

use App\Actions\TechTip\TechTipSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\SearchTipsRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchTipsController extends Controller
{
    /**
     * Perform a Tech Tip Search based on supplied parameters.
     */
    public function __invoke(SearchTipsRequest $request, TechTipSearch $svc): mixed
    {
        return $svc($request->safe()->collect());
    }
}
