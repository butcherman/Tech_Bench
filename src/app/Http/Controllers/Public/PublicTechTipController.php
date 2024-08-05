<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Http\Resources\PublicTechTipResource;
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
     * Search for a Public Tech Tip
     */
    public function search(SearchTipsRequest $request)
    {
        $searchObj = new TechTipSearchService($request);
        return $searchObj->publicSearch();
    }

    /**
     * Display the specified resource.
     */
    public function show(TechTip $tip_slug)
    {
        $this->authorize('view', $tip_slug);

        $tip_slug->makeHidden([
            'user_id',
            'updated_id',
            'sticky',
            'allow_comments',
            'slug',
            'views',
            // 'created_at',
            // 'updated_at',
            'href',
            'equipList',
            'fileList',
            // 'equipment_type',
            'file_upload',
        ]);
        $tip_slug->load([
            'EquipmentType' => function ($q) {
                $q->where('allow_public_tip', true);
            }
        ]);
        $tip_slug->EquipmentType->makeHidden([
            'allow_public_tip',
            'cat_id',
            'equip_id',
        ]);
        $tip_slug->load([
            'FileUpload' => function ($q) {
                $q->where('public', true);
            }
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
