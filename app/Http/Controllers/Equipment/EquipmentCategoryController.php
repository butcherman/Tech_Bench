<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\RecordInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * All Equipment is placed under a specific Equipment Category
 */
class EquipmentCategoryController extends Controller
{
    /**
     * Store a newly created Category
     */
    public function store(EquipmentCategoryRequest $request)
    {
        $newCategory = EquipmentCategory::create($request->only(['name']));

        Log::info('New Equipment Category '.$newCategory->name.' has been created by '.$request->user()->username);

        return back()->with('success', __('equipment.category.created'));
    }

    /**
     * Update the specified Category name
     */
    public function update(EquipmentCategoryRequest $request, EquipmentCategory $equipment_category)
    {
        $equipment_category->update($request->only(['name']));

        Log::info('Equipment ID '.$equipment_category->cat_id.' has been updated by '.$request->user()->username, $request->toArray());

        return back()->with('success', __('equipment.category.updated'));
    }

    /**
     * Remove the specified category
     * Note:  Category cannot be removed if it has equipment assigned to it
     */
    public function destroy(Request $request, EquipmentCategory $equipment_category)
    {
        $this->authorize('delete', $equipment_category);

        try {
            $equipment_category->delete();
        } catch (QueryException $e) {
            //  If the model is still in use, throw a unique exception
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(__('equipment.category.in-use', ['name' => $equipment_category->name]), 0, $e);
            }
            // @codeCoverageIgnoreStart
            Log::error('Error when trying to delete Equipment Category '.$equipment_category->name, $e->errorInfo);

            return back()->withErrors(['error' => __('equipment.category.destroy-failed')]);
            // @codeCoverageIgnoreEnd
        }

        Log::notice('Equipment Category '.$equipment_category->name.' has been deleted by '.$request->user()->username);

        return back()->with('warning', __('equipment.category.destroyed'));
    }
}
