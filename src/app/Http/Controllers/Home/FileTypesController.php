<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\FileTypesRequest;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Service\Cache;
use App\Service\Customer\CustomerFileTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileTypesController extends Controller
{
    public function __construct(protected CustomerFileTypeService $svc) {}

    /**
     * Show a listing of all current File Types (from cache)
     */
    public function index(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/FileType/Index', [
            'file-types' => Cache::fileTypes(),
        ]);
    }

    /**
     * Return the File Types from Cache
     */
    public function create(): JsonResponse
    {
        return response()->json(Cache::fileTypes());
    }

    /**
     * Store a newly created File Type.
     */
    public function store(FileTypesRequest $request): RedirectResponse
    {
        $this->svc->createFileType($request->collect());

        return back()->with('success', __('admin.file-type.created'));
    }

    /**
     * Update the File Type.
     */
    public function update(
        FileTypesRequest $request,
        CustomerFileType $file_type
    ): RedirectResponse {
        $this->svc->updateFileType($request->collect(), $file_type);

        return back()->with('success', __('admin.file-type.updated'));
    }

    /**
     * Remove the File Type.
     */
    public function destroy(CustomerFileType $file_type): RedirectResponse
    {
        $this->authorize('manage', Customer::class);

        $this->svc->destroyFileType($file_type);

        return back()->with('warning', __('admin.file-type.destroyed'));
    }
}
