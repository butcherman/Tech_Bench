<?php

namespace App\Http\Controllers\TechTip\Public;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicTipController extends Controller
{
    /**
     * Show the Tech Tip Search Page
     */
    public function index()
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
    public function show(TechTip $tech_tip)
    {
        return 'show public tip';
    }
}
