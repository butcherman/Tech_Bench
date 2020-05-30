<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Equipment\GetCategory;
use App\Domains\Equipment\SetCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\CategoryRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EquipmentCategoriesController extends Controller
{
    //  Show for to creat a new equipment category
    public function create()
    {
        return view('admin.equipment.editCategory', [
            'data' => null,
        ]);
    }

    //  Store the new equipment category
    public function store(CategoryRequest $request)
    {
        (new SetCategory)->createCategory($request);
        Log::notice('New Equipment Category created by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Edit an existing category
    public function edit($id)
    {
        return view('admin.equipment.editCategory', [
            'data' => (new GetCategory)->getCategoryData($id),
        ]);
    }

    //  Update an existing category
    public function update(CategoryRequest $request, $id)
    {
        (new SetCategory)->updateCategory($request, $id);
        Log::notice('Category ID '.$id.' - '.$request->name.' has been updated by '.Auth::user()->full_name);

        return response()->json(['success' => true]);
    }

    //  Delete an equipment category - note:  this will fail if equipment is assigned to the category
    public function destroy($id)
    {
        $res = (new SetCategory)->deleteCategory($id);
        if($res)
        {
            Log::notice('Equipment Category ID '.$id.' has been deleted by '.Auth::user()->full_name);
        }

        return response()->json(['success' => $res]);
    }
}
