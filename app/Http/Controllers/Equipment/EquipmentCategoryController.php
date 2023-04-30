<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentCategoryController extends Controller
{
    /**
     * Form to create a new category
     */
    public function create()
    {
        $this->authorize('create', EquipmentCategory::class);

        return Inertia::render('Equipment/Category/Create');
    }

    /**
     * Store a new Category
     */
    public function store(EquipmentCategoryRequest $request)
    {
        $newCat = EquipmentCategory::create($request->only(['name']));

        Log::info('New Equipment Category '.$newCat->name.' has been created by '.$request->user()->username);

        return redirect(route('equipment.index'))->with('success', __('equip.category.created'));
    }

    /**
     * Show the form for editing the category
     */
    public function edit(EquipmentCategory $equipment_category)
    {
        $this->authorize('update', $equipment_category);

        return Inertia::render('Equipment/Category/Edit', [
            'category' => $equipment_category,
        ]);
    }

    /**
     * Update the specified Category
     */
    public function update(EquipmentCategoryRequest $request, EquipmentCategory $equipment_category)
    {
        $equipment_category->update($request->only(['name']));

        Log::info('Equimpent ID '.$equipment_category->equip_id.' has been updated by '.$request->user()->username);

        return redirect(route('equipment.index'))->with('success', __('equip.category.updated'));
    }

    /**
     * Remove the a category
     */
    public function destroy(EquipmentCategory $equipment_category)
    {
        $this->authorize('delete', $equipment_category);

        try {
            $equipment_category->delete();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 19 || $e->errorInfo[1] === 1451) {
                Log::error('Unable to delete Equipment Category '.$equipment_category->name.'.  It is currently in use');

                return back()->withErrors([
                    'error' => __('equip.category.in_use'),
                    // 'link'  => '<a html="#">More Info</a>',
                ]);
            }

            // @codeCoverageIgnoreStart
            Log::error('Error when trying to delete Equipment Category '.$equipment_category->name, $e->errorInfo);

            return back()->withErrors(['error' => __('equip.category.del_failed')]);
            // @codeCoverageIgnoreEnd
        }

        Log::notice('Equipment Category '.$equipment_category->name.' has been deleted by '.Auth::user()->username);

        return redirect(route('equipment.index'))->with('success', __('equip.category.destroyed'));
    }
}
