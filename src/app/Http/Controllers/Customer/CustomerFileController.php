<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Service\Customer\CustomerFileService;
use App\Traits\FileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CustomerFileController extends Controller
{
    use FileTrait;

    public function __construct(protected CustomerFileService $svc) {}

    /**
     * Store a newly created Customer File in storage.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        $this->svc->processIncomingFile($request, $customer);

        return response()->noContent();
    }

    /**
     * Update the specified Customer File in storage.
     */
    public function update(
        CustomerFileRequest $request,
        Customer $customer,
        CustomerFile $file
    ): RedirectResponse {
        $this->svc->updateCustomerFile($request, $file);

        return back()->with('success', __('cust.file.updated'));
    }

    /**
     * Remove the specified Customer File from storage.
     */
    public function destroy(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('delete', $file);

        $this->svc->destroyCustomerFile($file);

        return back()->with('warning', __('cust.file.deleted'));
    }

    /**
     * Restore a soft deleted file
     */
    public function restore(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('restore', $file);

        $this->svc->restoreCustomerFile($file);

        return back()->with('success', __('cust.file.restored'));
    }

    /**
     * Remove a soft deleted file
     */
    public function forceDelete(Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('force-delete', $file);

        $this->svc->destroyCustomerFile($file, true);

        return back()
            ->with('warning', __('cust.file.force_deleted'));
    }
}
