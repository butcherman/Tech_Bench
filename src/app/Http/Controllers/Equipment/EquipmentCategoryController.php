<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\Database\RecordInUseException;
use App\Exceptions\Database\GeneralQueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipmentCategoryController extends Controller
{
    /**
     * Store the newly created resource in storage.
     */
    public function store(EquipmentCategoryRequest $request)
    {
        $newCat = EquipmentCategory::create($request->all());

        Log::info('New Equipment Category ' . $newCat->name . ' created by ' .
            $request->user()->username, $newCat->toArray());

        return back()->with('success', __('equipment.category.created'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(EquipmentCategoryRequest $request, EquipmentCategory $category)
    {
        $category->update($request->all());

        Log::info('Equipment Category ' . $category->name . ' has been updated by ' .
            $request->user()->username);

        return back()->with('success', __('equipment.category.updated'));
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(Request $request, EquipmentCategory $category)
    {
        try {
            $category->delete();
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(
                    $category->name . ' is still in use and cannot be deleted',
                    0,
                    $e
                );
            } else {
                throw new GeneralQueryException('', 0, $e);
            }
        }

        Log::notice('Equipment Category ' . $category->name . ' has been deleted by ' .
            $request->user()->username);

        return back()->with('warning', __('equipment.category.destroyed'));
    }
}
