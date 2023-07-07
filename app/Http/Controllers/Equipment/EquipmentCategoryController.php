<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentCategoryRequest $request)
    {
        $newCategory = EquipmentCategory::create($request->only(['name']));

        Log::info('New Equipment Category '.$newCategory->name.' has been created by '.$request->user()->username);

        return back()->with('success', 'created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentCategoryRequest $request, EquipmentCategory $equipment_category)
    {
        $equipment_category->update($request->only(['name']));

        Log::info('Equipment ID '.$equipment_category->cat_id.' has been updated by '.$request->user()->username, $request->toArray());

        return back()->with('success', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, EquipmentCategory $equipment_category)
    {
        $this->authorize('delete', $equipment_category);

        try {
            $equipment_category->delete();
        } catch(Exception $e) {
            dd(get_class($e));
        }

        Log::notice('Equipment Category '.$equipment_category->name.' has been deleted by '.$request->user()->username);

        return back()->with('warning', 'deleted');
    }
}
