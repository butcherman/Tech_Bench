<?php

namespace App\Http\Controllers\Equip;

use Inertia\Inertia;

use App\Models\EquipmentType;
use App\Models\EquipmentCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        Log::info('New Equipment Category '.$request->name.' created by '.Auth::user()->full_name);
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
        Log::info('Equipment Category ID '.$id.' name - '.$request->name.' was updated by '.Auth::user()->full_name);
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
            Log::notice('User '.Auth::user()->full_name.' is trying to delete Equipment Category '.$equip->name.' but it is still in use');
            return back()->with(['message' => 'This category has Equipment assigned to it.  Please delete this equipment before continuing', 'type' => 'danger']);
        }

        Log::notice('Equipment Category ID '.$id.' name - '.$equip->name.' has been deleted by '.Auth::user()->full_name);
        $equip->delete();
        return redirect(route('admin.index'))->with(['message' => 'Category Deleted', 'type' => 'success']);
    }
}
