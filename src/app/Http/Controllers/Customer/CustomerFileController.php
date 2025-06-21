<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Facades\CacheData;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Services\Customer\CustomerFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CustomerFileController extends FileUploadController
{
    public function __construct(protected CustomerFileService $svc) {}

    /**
     * Get a list of possible files and equipment to assign to a customer file.
     */
    public function index(Customer $customer): JsonResponse
    {
        return response()->json([
            'equipmentList' => $customer->Equipment,
            'fileTypes' => CacheData::fileTypes(),
        ]);
    }

    /**
     * Save and store an uploaded file.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        // Process the file upload first
        $this->setFileData(DiskEnum::customers, $customer->cust_id);
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            $this->svc->createCustomerFile(
                $request->safe()->collect(),
                $savedFile,
                $customer,
                $request->user()
            );
        }

        return response()->noContent();
    }

    /**
     * Update the Customer File.
     */
    public function update(
        CustomerFileRequest $request,
        Customer $customer,
        CustomerFile $file
    ): RedirectResponse {
        $this->svc->updateCustomerFile($request->safe()->collect(), $file);

        return back()->with('success', __('cust.file.updated'));
    }

    /**
     * Remove the Customer File.
     */
    public function destroy(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('delete', $file);

        $this->svc->destroyCustomerFile($file);

        return back()->with('warning', __('cust.file.deleted'));
    }

    /**
     * Restore the Customer File.
     */
    public function restore(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('restore', $file);

        $this->svc->restoreCustomerFile($file);

        return back()->with('success', __('cust.file.restored'));
    }

    /**
     * Force Delete the Customer File.
     */
    public function forceDelete(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('force-delete', $file);

        $this->svc->destroyCustomerFile($file, true);

        return back()->with('warning', __('cust.file.force_deleted'));
    }
}
