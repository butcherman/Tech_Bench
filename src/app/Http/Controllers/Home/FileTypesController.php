<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\FileTypesRequest;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Service\Cache;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/FileType/Index', [
            'file-types' => Cache::fileTypes(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Cache::fileTypes();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileTypesRequest $request)
    {
        $fileType = CustomerFileType::create($request->only(['description']));

        Cache::clearCache(['fileTypes']);
        Log::info('New Customer File Type created by '.
            $request->user()->username, $fileType->toArray());

        return back()->with('success', __('admin.file-type.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FileTypesRequest $request, CustomerFileType $file_type)
    {
        $file_type->update($request->only(['description']));

        Cache::clearCache(['fileTypes']);
        Log::info('Customer File Type Updated by '.
            $request->user()->username, $file_type->toArray());

        return back()->with('success', __('admin.file-type.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CustomerFileType $file_type)
    {
        $this->authorize('manage', Customer::class);

        try {
            $file_type->delete();
            Cache::clearCache(['fileTypes']);
        } catch (QueryException $e) {
            CheckDatabaseError::check($e, 'Unable to delete, File Type is in use by at least one customer');
        }

        return back()->with('warning', __('admin.file-type.destroyed'));
    }
}
