<?php

namespace App\Http\Controllers\Installer;

use App\SystemCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //  View the list of categories
    public function index()
    {
        $categories = SystemCategories::all();
        
        return view('installer.categoryList', [
            'cats' => $categories
        ]);
    }

    //  New category form
    public function create()
    {
        return view('installer.newCategory');
    }

    //  Store the new category form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);
        
        $cat = SystemCategories::create([
            'name' => $request->name
        ]);
        
        Log::info('New System Category Created', ['cat_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        
        Log::info('New System Category - '.$request->name.' created by User ID-'.Auth::user()->user_id);
        
        return redirect()->back()->with('success', 'Category Successfully Added. <a href="'.route('installer.systems.create').'">Add System</a>');
    }

    //  Brind up the Edit System F
    public function show($id)
    {
        //
        echo 'show';
    }

    //  Brind up the Edit Category Form
    public function edit($id)
    {
        $cat = SystemCategories::find($id);
        
        if(!$cat)
        {
            return response(404);
        }
        
        return view('installer.editCategory', [
            'details' => $cat
        ]);
    }

    //  Submit teh Edit Category form
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                    'required',
                    'string',
                    Rule::unique('system_categories')->ignore($id, 'cat_id'),
                    'regex:/^[a-zA-Z0-9_ ]*$/'
                ]
        ]);
        
        SystemCategories::find($id)->update([
            'name' => $request->name
        ]);
        
        return redirect()->back()->with('success', 'Category Successfully Modified. <a href="'.route('installer.systems.create').'">Add System</a>');
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
