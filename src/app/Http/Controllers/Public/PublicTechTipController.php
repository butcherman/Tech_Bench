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
        $searchObj = new TechTipSearchService($request);

        return $searchObj->publicSearch();
    }

    /**
     * Display the Tech Tip
     */
    public function show(TechTip $tech_tip)
    {
        $tech_tip->loadPublicData();

        return Inertia::render('Public/TechTips/Show', [
            'tip-data' => $tech_tip,
        ]);
    }
}
