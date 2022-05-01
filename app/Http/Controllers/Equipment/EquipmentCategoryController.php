<?php

namespace App\Http\Controllers\Equipment;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

use App\Models\EquipmentCategory;
use App\Events\Equipment\EquipmentCategoryCreatedEvent;
use App\Events\Equipment\EquipmentCategoryDeletedEvent;
use App\Events\Equipment\EquipmentCategoryUpdatedEvent;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;

class EquipmentCategoryController extends Controller
{
    /**
     * Show the form for creating a new Equipment Category
     */
    public function create()
    {
        $this->authorize('create', EquipmentCategory::class);

        return Inertia::render('EquipmentCategory/Create');
    }

    /**
     * Store a newly created Equipment Category
     */
    public function store(EquipmentCategoryRequest $request)
    {
        $newCat = EquipmentCategory::create($request->only('name'));

        event(new EquipmentCategoryCreatedEvent($newCat));
        return redirect(route('equipment.index'))->with([
            'message' => 'New Category Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Show the form for editing the Category Name
     */
    public function edit($id)
    {
        $cat = EquipmentCategory::findOrFail($id);
        $this->authorize('update', $cat);

        return Inertia::render('EquipmentCategory/Edit', [
            'category' => $cat,
        ]);
    }

    /**
     * Update the Equipment Category Name
     */
    public function update(EquipmentCategoryRequest $request, $id)
    {
        $cat = EquipmentCategory::find($id);
        $cat->update($request->only('name'));

        event(new EquipmentCategoryUpdatedEvent($cat));
        return redirect(route('equipment.index'))->with([
            'message' => 'Category Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the Equipment Category
     */
    public function destroy($id)
    {
        $cat = EquipmentCategory::find($id);
        $this->authorize('delete', $cat);
        $cat->delete();

        event(new EquipmentCategoryDeletedEvent($cat));
        return back()->with([
            'message' => 'Equipment Category Deleted',
            'type'    => 'danger',
        ]);
    }
}
