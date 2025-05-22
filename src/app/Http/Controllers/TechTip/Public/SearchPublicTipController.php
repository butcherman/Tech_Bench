<?php

namespace App\Http\Controllers\TechTip\Public;

use App\Actions\TechTip\TechTipSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\SearchTipsRequest;
use App\Http\Resources\TechTip\PublicTipResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchPublicTipController extends Controller
{
    /**
     * Search for a Tech Tip but only Tips marked as Public.
     */
    public function __invoke(SearchTipsRequest $request, TechTipSearch $svc): mixed
    {
        $res = $svc($request->safe()->collect(), true);

        // Hide potentially sensitive data
        foreach ($res as $model) {
            $model->makeHidden([
                'allow_comments',
                'href',
                'type',
                'updated_id',
                'user_id',
                'views'
            ]);
        }

        return $res;
    }
}
