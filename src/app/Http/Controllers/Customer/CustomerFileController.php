<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerFileController extends Controller
{
    use FileTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerFileRequest $request, Customer $customer)
    {
        $this->setFileData('customers', $customer->cust_id);
        if ($savedFile = $this->getChunk($request)) {
            $newFile = $request->createFile($savedFile);

            Log::channel('cust')->info(
                'New Customer File created for ' .
                $customer->name . ' by ' . $request->user()->username,
                $newFile->toArray()
            );

            return response()->noContent();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFileRequest $request, Customer $customer, CustomerFile $file)
    {
        $request->updateFile();

        Log::channel('cust')
            ->info('Customer File Information updated by ' . $request->user()->username, $file->toArray());

        return back()->with('success', __('cust.file.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerFile $file)
    {
        $this->authorize('delete', $file);

        $file->delete();

        Log::channel('cust')
            ->notice('Customer File deleted for ' . $customer->name . ' by ' .
                $request->user()->username, $file->toArray());

        return back()->with('warning', __('cust.file.deleted'));
    }

    /**
     * Restore a soft deleted file
     */
    public function restore(Request $request, Customer $customer, CustomerFile $file)
    {
        $this->authorize('restore', $file);

        $file->restore();
        Log::channel('cust')
            ->info('Customer File restored for ' . $customer->name . ' by ' .
                $request->user()->username, $file->toArray());

        return back()->with('success', __('cust.file.restored'));
    }

    /**
     * remove a soft deleted file
     */
    public function forceDelete(Request $request, Customer $customer, CustomerFile $file)
    {
        $this->authorize('force-delete', $file);

        Log::channel('cust')
            ->notice('Customer File force deleted for ' . $customer->name .
                ' by ' . $request->user()->username, $file->toArray());
        $file->forceDelete();

        return back()
            ->with('warning', __('cust.file.force_deleted'));
    }
}
