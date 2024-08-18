<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\EquipmentCategory;
use App\Models\TechTip;
use App\Service\TechTipSearchService;
use Inertia\Inertia;

class PublicTechTipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! config('techTips.allow_public')) {
            abort(404);
        }

        return Inertia::render('Public/TechTips/Index', [
            'equip-types' => EquipmentCategory::with([
                'EquipmentType' => function ($q) {
                    $q->where('allow_public_tip', true);
                },
            ])->get(),
        ]);
    }

    /**
     * Search for a Public Tech Tip
     */
    public function search(SearchTipsRequest $request)
    {
        if (! config('techTips.allow_public')) {
            abort(404);
        }

        $searchObj = new TechTipSearchService($request);

        return $searchObj->publicSearch();
    }

    /**
     * Display the specified resource.
     */
    public function show(TechTip $tip_slug)
    {
        if (! config('techTips.allow_public') || ! $tip_slug->public) {
            abort(404);
        }

        $tip_slug->makeHidden([
            'user_id',
            'updated_id',
            'sticky',
            'allow_comments',
            'slug',
            'views',
            'href',
            'equipList',
            'fileList',
        ]);
        $tip_slug->load([
            'EquipmentType' => function ($q) {
                $q->where('allow_public_tip', true);
            },
        ]);
        $tip_slug->EquipmentType->makeHidden([
            'allow_public_tip',
            'cat_id',
            'equip_id',
        ]);
        $tip_slug->load([
            'FileUpload' => function ($q) {
                $q->where('public', true);
            },
        ]);
        $tip_slug->FileUpload->makeHidden([
            'file_size',
            'pivot',
        ]);

        return Inertia::render('Public/TechTips/Show', [
            'tip-data' => $tip_slug,
        ]);
    }
}
