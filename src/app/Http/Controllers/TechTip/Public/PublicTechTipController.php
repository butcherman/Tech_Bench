<?php

namespace App\Http\Controllers\TechTip\Public;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicTechTipResource;
use App\Models\TechTip;
use Inertia\Inertia;
use Inertia\Response;

class PublicTechTipController extends Controller
{
    /**
     * Display search page for Public Tech Tips
     */
    public function index(): Response
    {
        return Inertia::render('Public/TechTips/Index', [
            'equip-types' => fn () => CacheFacade::publicEquipmentCategories(),
        ]);
    }

    /**
     * Display the Public Tech Tip.
     */
    public function show(TechTip $tech_tip): Response
    {
        $tech_tip->wasViewed();

        return Inertia::render('Public/TechTips/Show', [
            'tip-data' => fn () => new PublicTechTipResource($tech_tip),
            'tip-equipment' => fn () => $tech_tip->PublicEquipmentType,
            'tip-files' => fn () => $tech_tip->FileUpload,
        ]);
    }
}
