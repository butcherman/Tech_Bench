<?php

namespace App\Http\Controllers\Installer;

use App\SystemCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->authorize('hasAccess', 'Manage Equipment');
            return $next($request);
        });

    }
    //  View the list of categories
    public function index()
    {
        $categories = SystemCategories::all();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.categoryList', [
            'cats' => $categories
        ]);
    }

    //  New category form
    // public function create()
    // {
    //     Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
    //     return view('installer.newCategory');
    // }

    //  Store the new category form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);

        SystemCategories::create([
            'name' => $request->name
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('New System Category - '.$request->name.' created by User ID-'.Auth::user()->user_id);

        return response()->json(['success' => true]);
        // return redirect()->back()->with('success', 'Category Successfully Added. <a href="'.route('installer.systems.create').'">Add System</a>');
    }

    //  Get a JSON list of the categories
    // public function show($id)
    // {
    //     $categories = SystemCategories::all();

    //     Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
    //     return response()->json($categories);
    // }

    //  Brind up the Edit Category Form
    // public function edit($id)
    // {
    //     $cat = SystemCategories::find($id);

    //     if(!$cat)
    //     {
    //         Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
    //         Log::debug('Invalid Category ID Selected - '.$id);
    //         return response(404);
    //     }

    //     Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
    //     Log::debug('Edit Data - ', $cat->toArray());
    //     return view('installer.editCategory', [
    //         'details' => $cat
    //     ]);
    // }

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

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('Category ID-'.$id.' updated by User ID-'.Auth::user()->user_id);
        // return redirect()->back()->with('success', 'Category Successfully Modified. <a href="'.route('installer.systems.create').'">Add System</a>');
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            SystemCategories::find($id)->delete();
            return response()->json(['success' => true, 'reason' => 'Category Successfully Deleted']);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            return response()->json(['success' => false, 'reason' => 'Category still in use.  You must delete all systems attached to this category first.']);
        }
    }
}
