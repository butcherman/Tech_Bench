<?php

namespace App\Http\Controllers\Customer;

use App\Enum\CrudAction;
use App\Events\Customer\CustomerFileEvent;
use App\Events\File\FileDataDeletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Traits\FileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomerFileController extends Controller
{
    use FileTrait;

    /**
     * Store a newly created Customer File in storage.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        $this->setFileData('customers', $customer->cust_id);
        if ($savedFile = $this->getChunk($request)) {
            $newFile = $request->createFile($savedFile);

            Log::channel('cust')->info(
                'New Customer File created for '.
                $customer->name.' by '.$request->user()->username,
                $newFile->toArray()
            );

            event(new CustomerFileEvent($customer, $newFile, CrudAction::Create));
        }

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
        $request->updateFile();

        Log::channel('cust')
            ->info(
                'Customer File Information updated by '.$request->user()->username,
                $file->toArray()
            );

        event(new CustomerFileEvent($customer, $file, CrudAction::Update));

        return back()->with('success', __('cust.file.updated'));
    }

    /**
     * Remove the specified Customer File from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('delete', $file);

        $file->delete();

        Log::channel('cust')
            ->notice('Customer File deleted for '.$customer->name.' by '.
                $request->user()->username, $file->toArray());

        event(new CustomerFileEvent($customer, $file, CrudAction::Destroy));

        return back()->with('warning', __('cust.file.deleted'));
    }

    /**
     * Restore a soft deleted file
     */
    public function restore(Request $request, Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('restore', $file);

        $file->restore();

        Log::channel('cust')
            ->info('Customer File restored for '.$customer->name.' by '.
                $request->user()->username, $file->toArray());

        event(new CustomerFileEvent($customer, $file, CrudAction::Restore));

        return back()->with('success', __('cust.file.restored'));
    }

    /**
     * Remove a soft deleted file
     */
    public function forceDelete(Request $request, Customer $customer, CustomerFile $file): RedirectResponse
    {
        $this->authorize('force-delete', $file);

        $file->forceDelete();

        Log::channel('cust')
            ->notice('Customer File force deleted for '.$customer->name.
                ' by '.$request->user()->username, $file->toArray());

        event(new CustomerFileEvent($customer, $file, CrudAction::ForceDelete));
        event(new FileDataDeletedEvent($file->FileUpload->file_id));

        return back()
            ->with('warning', __('cust.file.force_deleted'));
    }
}
