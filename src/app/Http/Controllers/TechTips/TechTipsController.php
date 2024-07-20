<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipRequest;
use App\Models\EquipmentCategory;
use App\Models\TechTip;
use App\Models\TechTipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('TechTips/Index', [
            'filter-data' => [
                'tip_types' => TechTipType::all(),
                'equip_types' => EquipmentCategory::with('EquipmentType')->get(),
            ],
            'can-create' => $request->user()->can('create', TechTip::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip-types' => TechTipType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TechTipRequest $request)
    {
        //
        Log::critical('working');
        return 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
