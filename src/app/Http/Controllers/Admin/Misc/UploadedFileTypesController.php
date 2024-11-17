<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Misc\UploadedFileTypesRequest;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Services\Customer\CustomerFileTypeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UploadedFileTypesController extends Controller
{
    public function __construct(protected CustomerFileTypeService $svc) {}

    /**
     * Show a listing of all current File Types (from cache)
     */
    public function index(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/FileType/Index', [
            'file-types' => CacheFacade::fileTypes(),
        ]);
    }

    /**
     * Store a newly created File Type.
     */
    public function store(UploadedFileTypesRequest $request): RedirectResponse
    {
        $this->svc->createFileType($request->safe()->collect());

        return back()->with('success', __('admin.file-type.created'));
    }

    /**
     * Update the File Type.
     */
    public function update(
        UploadedFileTypesRequest $request,
        CustomerFileType $file_type
    ): RedirectResponse {
        $this->svc->updateFileType($request->safe()->collect(), $file_type);

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
