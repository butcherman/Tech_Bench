<?php

namespace App\Http\Controllers\Equipment;

use App\Actions\OrderEquipDataTypes;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    /**
     * Landing page for the Equipment Admin Page
     */
    public function index()
    {
        return Inertia::render('Equipment/Index', [
            'cat-list' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Show the form for creating a new Equipment type
     */
    public function create(EquipmentCategory $equipmentCategory)
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category'  => $equipmentCategory,
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Store a newly created Equipment Type
     */
    public function store(EquipmentTypeRequest $request)
    {
        $equipment         = new EquipmentType($request->only(['name']));
        $equipment->cat_id = EquipmentCategory::where('name', $request->category)->first()->cat_id;
        $equipment->save();

        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);
        Log::info('New Equipiment Type created by '.$request->user()->username, $request->toArray());

        return redirect(route('equipment.index'))->with('success', __('equip.created'));
    }

    /**
     * Show the form for editing the Equipment
     */
    public function edit(EquipmentType $equipment)
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'equipment' => $equipment->load(['EquipmentCategory', 'DataFieldType']),
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Update the specified equipment
     */
    public function update(EquipmentTypeRequest $request, EquipmentType $equipment)
    {
        $equipment->name = $request->name;
        $equipment->save();

        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);



        return redirect(route('equipment.index'))->with('success', __('equip.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
