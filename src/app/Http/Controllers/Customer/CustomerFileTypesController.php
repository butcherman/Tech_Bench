<?php

namespace App\Http\Controllers\Customer;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Misc\UploadedFileTypesRequest;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Services\Customer\CustomerFileTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerFileTypesController extends Controller
{
    public function __construct(protected CustomerFileTypeService $svc) {}

    /**
     * Show a list of the current Customer File Types
     */
    public function index(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/FileType/Index', [
            'file-types' => CacheData::fileTypes(),
        ]);
    }

    /**
     * Store a newly created Customer File Type
     */
    public function store(UploadedFileTypesRequest $request): RedirectResponse
    {
        $this->svc->createFileType($request->safe()->collect());

        return back()->with('success', __('admin.file-type.created'));
    }

    /**
     * Update the name of an existing Customer File Type
     */
    public function update(UploadedFileTypesRequest $request, CustomerFileType $file_type): RedirectResponse
    {
        $this->svc->updateFileType($request->safe()->collect(), $file_type);

        return back()->with('success', __('admin.file-type.updated'));
    }

    /**
     * Delete a Customer File Type.  This process will fail if in use
     */
    public function destroy(CustomerFileType $file_type): RedirectResponse
    {
        $this->authorize('manage', Customer::class);

        $this->svc->destroyFileType($file_type);

        return back()->with('warning', __('admin.file-type.destroyed'));
    }
}
