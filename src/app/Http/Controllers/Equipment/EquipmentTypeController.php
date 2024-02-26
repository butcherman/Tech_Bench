<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Service\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Equipment/Index', [
            'equipment-list' => fn() => Cache::equipmentCategories(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Equipment/Create', [
            'category-list' => fn() => Cache::equipmentCategories(),
            'data-list' => fn() => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentTypeRequest $request)
    {
        $newEquipment = EquipmentType::create($request->only(['cat_id', 'name']));
        $request->processCustomerFields($newEquipment);

        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        Log::info(
            'New Equipment Type created by ' . $request->user()->username,
            $request->toArray()
        );

        return redirect(route('equipment.index'))->with('success', __('equipment.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentType $equipment)
    {
        return Inertia::render('Equipment/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentType $equipment)
    {
        return Inertia::render('Equipment/Edit', [
            'equipment' => fn() => $equipment->load('DataFieldType'),
            'category-list' => fn() => Cache::equipmentCategories(),
            'data-list' => fn() => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentTypeRequest $request, EquipmentType $equipment)
    {
        $equipment->update($request->only(['cat_id', 'name']));
        $request->processCustomerFields($equipment);

        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        Log::info('Equipment Type ' . $equipment->name . ' updated by ' .
            $request->user()->username, $request->toArray());

        UpdateCustomerDataFieldsJob::dispatch($equipment);

        return redirect(route('equipment.index'))->with('success', __('equipment.updated'));
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
