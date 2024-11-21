<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Services\Customer\CustomerFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CustomerFileController extends FileUploadController
{
    public function __construct(protected CustomerFileService $svc) {}

    /**
     * Store a newly created Customer File.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        // Process File Upload First
        $this->setFileData(DiskEnum::customers, $customer->cust_id);
        $savedFile = $this->getChunk($request->file('file'), $request);

        // If upload is complete, continue processing request
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

        return back()
            ->with('warning', __('cust.file.force_deleted'));
    }
}
