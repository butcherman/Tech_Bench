<?php

namespace App\Http\Controllers\TechTip\Public;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Inertia\Inertia;
use Inertia\Response;

class PublicTipController extends Controller
{
    /**
     * Show the Tech Tip Search Page
     */
    public function index(): Response
    {
        return Inertia::render('TechTip/Public/Index', [
            'filter-data' => [
                'tip_types' => [],
                'equip_types' => CacheData::publicEquipmentCategories(),
            ],
        ]);
    }

    /**
     * Show a specific Public Tech Tip
     */
    public function show(TechTip $tech_tip): Response
    {
        return Inertia::render('TechTip/Public/Show', [
            'equipment' => fn () => $tech_tip->PublicEquipment->makeHidden([
                'equip_id',
                'cat_id',
                'allow_public_tip',
            ]),
            'files' => fn () => $tech_tip->Files->makeHidden([
                'created_stamp',
                'pivot',
            ]),
            'tech-tip' => $tech_tip->makeHidden([
                'allow_comments',
                'href',
                'type',
                'updated_id',
                'user_id',
                'views',
                'Files',
                'PublicEquipment',
            ]),
        ]);
    }
}
