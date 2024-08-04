<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Service\TechTipSearchService;
use Illuminate\Http\Request;
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
                }
            ])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function search(SearchTipsRequest $request)
    {
        // return TechTip::paginate();
        $searchObj = new TechTipSearchService($request);

        return $searchObj->publicSearch();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }
}
