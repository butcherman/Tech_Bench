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
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Manage Equipment');
            return $next($request);
        });

    }
    //  View the list of categories
    public function index()
    {
        $categories = SystemCategories::all();

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('installer.categoryList', [
            'cats' => $categories
        ]);
    }

    //  Store the new category form
    public function store(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);

        SystemCategories::create([
            'name' => $request->name
        ]);

        Log::info('New System Category - '.$request->name.' created by '.Auth::user()->full_name);

        return response()->json(['success' => true]);
    }

    //  Submit the Edit Category form
    public function update(Request $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

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

        Log::info('Category ID - '.$id.' updated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Delete an existing category - note this will fail if the category has systems assigned to it
    public function destroy($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        try
        {
            SystemCategories::find($id)->delete();
            Log::notice('Category ID '.$id.' deleted by '.Auth::user()->full_name);
            return response()->json(['success' => true, 'reason' => 'Category Successfully Deleted']);
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            Log::warning('User '.Auth::user()->full_name.' tried to delete category ID '.$id.' but was unable to since it is still in use.');
            return response()->json(['success' => false, 'reason' => 'Category still in use.  You must delete all systems attached to this category first.']);
        }
    }
}
