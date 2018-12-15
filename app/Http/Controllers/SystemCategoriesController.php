<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\SystemCategories;

class SystemCategoriesController extends Controller
{
    //  Bring up the system categories to possibly Edit
    public function index()
    {
        $categories = SystemCategories::all();
        
        return view('installer.selectCategory', [
            'cats' => $categories
        ]);
    }

    //  Show the new Category form
    public function create()
    {
        return view('installer.form.newCat');
    }

    //  Submit the new category form
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
        
        return redirect()->back()->with('success', 'Category Successfully Added. <a href="'.route('installer.newSys', urlencode($cat->name)).'">Add System</a>');
    }

    //  Bring up the Edit Category form
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat = SystemCategories::find($id);
        
        return view('installer.form.editCategory', [
            'details' => $cat
        ]);
    }

    //  Submit the modified category name
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);
        
        SystemCategories::find($id)->update([
            'name' => $request->name
        ]);
        
        return redirect()->back()->with('success', 'Category Successfully Modified. <a href="'.route('installer.newSys', urlencode($request->name)).'">Add System</a>');
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
