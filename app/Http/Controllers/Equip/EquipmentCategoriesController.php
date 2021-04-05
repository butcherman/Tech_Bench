<?php

namespace App\Http\Controllers\Equip;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentCategoriesController extends Controller
{
    /**
     *  Show a list of categories available to edit
     */
    public function index()
    {
        $this->authorize('create', EquipmentCategory::class);
        return Inertia::render('Equipment/listCategories', [
            'categories' => EquipmentCategory::all(),
        ]);
    }

    /**
     *  Form to create a new Equipment Category
     */
    public function create()
    {
        $this->authorize('create', EquipmentCategory::class);
        return Inertia::render('Equipment/category');
    }

    /**
     *  Store the new category
     */
    public function store(EquipmentCategoryRequest $request)
    {
        EquipmentCategory::create($request->only('name'));
        return redirect(route('admin.index'))->with(['message' => 'New Category Created', 'type' => 'success']);
    }

    /**
     *  Form to edit a category
     */
    public function edit($id)
    {
        $category = EquipmentCategory::findOrFail($id);
        $this->authorize('update', $category);

        return Inertia::render('Equipment/category', [
            'cat' => $category,
        ]);
    }

    /**
     *  Update the selected category
     */
    public function update(EquipmentCategoryRequest $request, $id)
    {
        EquipmentCategory::findOrFail($id)->update($request->only('name'));

        return redirect(route('admin.index'))->with(['message' => 'Category Updated', 'type' => 'success']);
    }

    /**
     *  Delete a category
     */
    public function destroy($id)
    {
        $equip = EquipmentCategory::findOrFail($id);
        $this->authorize('delete', $equip);

        //  Cannot delete a category if it is in use
        $inUse = EquipmentType::where('cat_id', $id)->count();
        if($inUse)
        {
            return back()->with(['message' => 'This category has Equipment assigned to it.  Please delete this equipment before continuing', 'type' => 'danger']);
        }

        $equip->delete();
        return redirect(route('admin.index'))->with(['message' => 'Category Deleted', 'type' => 'success']);
    }
}
