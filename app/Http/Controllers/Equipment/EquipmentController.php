<?php

namespace App\Http\Controllers\Equipment;

use App\Actions\OrderEquipDataTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Equipment/Index', [
            'equip-list' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Equipment/Create', [
            'categories' => EquipmentCategory::all(),
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        $newEquip = EquipmentType::create($request->only(['cat_id', 'name']));
        (new OrderEquipDataTypes)->build($request->custData, $newEquip->equip_id);

        Log::info('New Equipment Type '.$request->name.' created by '.$request->user()->username, $request->toArray());
        return redirect(route('equipment.index'))->with('success', 'equipment created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Equipment/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentType $equipment)
    {
        return Inertia::render('Equipment/Edit', [
            'categories' => EquipmentCategory::all(),
            'data-list' => DataFieldType::all()->pluck('name'),
            'equipment' => $equipment->load('DataFieldType'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, EquipmentType $equipment)
    {
        $equipment->update($request->only(['cat_id', 'name']));
        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);

        Log::info('Equipment Type '.$request->name.' has been updated by '.$request->user()->username, $request->toArray());
        return redirect(route('equipment.index'))->with('success', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
